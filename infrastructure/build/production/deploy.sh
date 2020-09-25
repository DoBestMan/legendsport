#!/bin/bash

source infrastructure/build/deploy.fn.sh

CLOUDSDK_COMPUTE_REGION='us-central1'
CLOUDSDK_CONTAINER_CLUSTER='primary'
BACKEND_IMAGE="gcr.io/$PROJECT_ID/php:$SHORT_SHA"
FRONTEND_IMAGE="gcr.io/$PROJECT_ID/nginx:$SHORT_SHA"

BASE_DIR=./infrastructure/kubernetes/production
OUTPUT_DIR=/tmp/production
KUBERNETES_NAMESPACE="production"

echo "Deploying: $BACKEND_IMAGE and $FRONTEND_IMAGE"

REPLACEMENTS="\
s!######BACKEND_IMAGE######!${BACKEND_IMAGE}!g;\
s!######FRONTEND_IMAGE######!${FRONTEND_IMAGE}!g;\
"

prepare_manifests $BASE_DIR $REPLACEMENTS $OUTPUT_DIR

gcloud container clusters get-credentials --region "$CLOUDSDK_COMPUTE_REGION" "$CLOUDSDK_CONTAINER_CLUSTER"

kubectl apply -f $OUTPUT_DIR -n $KUBERNETES_NAMESPACE
