#!/bin/bash

CLOUDSDK_COMPUTE_REGION='us-central1'
CLOUDSDK_CONTAINER_CLUSTER='primary'
BACKEND_IMAGE="gcr.io/$PROJECT_ID/php:$SHORT_SHA"
FRONTEND_IMAGE="gcr.io/$PROJECT_ID/nginx:$SHORT_SHA"

mkdir /tmp/staging

cp infrastructure/kubernetes/staging/*.yaml /tmp/staging

sed "s/BACKEND_IMAGE/${BACKEND_IMAGE}/g" ./infrastructure/kubernetes/staging/templated/php-fpm.yaml | sed "s/FRONTEND_IMAGE/${FRONTEND_IMAGE}/g" > /tmp/staging/php-fpm.yaml

gcloud container clusters get-credentials --region "$CLOUDSDK_COMPUTE_REGION" "$CLOUDSDK_CONTAINER_CLUSTER"

kubectl apply -f /tmp/staging
