#!/bin/bash

source infrastructure/build/deploy.fn.sh

CLOUDSDK_COMPUTE_REGION='us-central1'
CLOUDSDK_CONTAINER_CLUSTER='primary'
BACKEND_IMAGE="gcr.io/$PROJECT_ID/php:$SHORT_SHA"
FRONTEND_IMAGE="gcr.io/$PROJECT_ID/nginx:$SHORT_SHA"
NAMESPACE="qaod-$BRANCH_NAME"
DOMAIN_NAME="$BRANCH_NAME.qa.legendsports.bet"
DATABASE_NAME="lsqa-$BRANCH_NAME"
DATABASE_INSTANCE="production8"

BASE_DIR=./infrastructure/kubernetes/qa
OUTPUT_DIR=/tmp/qa
KUBERNETES_NAMESPACE="qa"

echo "Deploying: $BACKEND_IMAGE and $FRONTEND_IMAGE"

if [[ ! $(gcloud sql databases list --instance=$DATABASE_INSTANCE | grep $DATABASE_NAME) ]]; then
    echo "Creating database: $DATABASE_NAME"
    gcloud sql databases create $DATABASE_NAME --instance=$DATABASE_INSTANCE
else
    echo "Database $DATABASE_NAME already exists"
fi;


REPLACEMENTS="\
s!######BACKEND_IMAGE######!${BACKEND_IMAGE}!g;\
s!######FRONTEND_IMAGE######!${FRONTEND_IMAGE}!g;\
s!######DOMAIN_NAME######!${DOMAIN_NAME}!g;\
s!######DATABASE_NAME######!${DATABASE_NAME}!g;\
s!######NAMESPACE######!${NAMESPACE}!g\
"

prepare_manifests $BASE_DIR $REPLACEMENTS $OUTPUT_DIR

gcloud container clusters get-credentials --region "$CLOUDSDK_COMPUTE_REGION" "$CLOUDSDK_CONTAINER_CLUSTER"

kubectl apply -f $OUTPUT_DIR -n $KUBERNETES_NAMESPACE
