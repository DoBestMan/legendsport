#!/bin/bash

BRANCH_NAME=$1
CLOUDSDK_COMPUTE_REGION='us-central1'
CLOUDSDK_CONTAINER_CLUSTER='primary'
NAMESPACE="qaod-$BRANCH_NAME"
DATABASE_NAME="lsqa-$BRANCH_NAME"

gcloud container clusters get-credentials --region "$CLOUDSDK_COMPUTE_REGION" "$CLOUDSDK_CONTAINER_CLUSTER"

kubectl delete BackendConfig "${NAMESPACE}websocket-backend-config" -n qa
kubectl delete CronJob "${NAMESPACE}scheduler" -n qa

kubectl delete Ingress "${NAMESPACE}web" -n qa
kubectl delete Ingress "${NAMESPACE}websockets" -n qa

kubectl delete Service "${NAMESPACE}web" -n qa
kubectl delete Deployment "${NAMESPACE}web" -n qa

kubectl delete Service "${NAMESPACE}websockets" -n qa
kubectl delete Deployment "${NAMESPACE}websockets" -n qa
kubectl delete Deployment "${NAMESPACE}worker" -n qa

kubectl delete ConfigMap "${NAMESPACE}php-environment" -n qa

gcloud sql databases delete $DATABASE_NAME --instance="production8"



