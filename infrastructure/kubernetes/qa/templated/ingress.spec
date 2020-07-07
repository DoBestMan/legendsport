apiVersion: networking.k8s.io/v1beta1
kind: Ingress
metadata:
  name: web
  annotations:
    kubernetes.io/ingress.global-static-ip-name: qa
spec:
    rules:
