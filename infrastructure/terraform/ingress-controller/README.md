https://github.com/zioproto/terraform-kubernetes-ingress-nginx-controller

# Kubernetes Ingress Nginx Controller

This module will deploy what you usually deploy doing:

    kubectl apply -f https://raw.githubusercontent.com/kubernetes/ingress-nginx/master/deploy/mandatory.yaml

# Example Usage
```
module "nginx-ingress-controller" {
  source = "./terraform-kubernetes-ingress-nginx-controller"
}
```


## Inputs

| Name | Description | Type | Default | Required |
|------|-------------|:----:|:-----:|:-----:|
| image | Docker image for the nginx-ingress-controller | string | quay.io/kubernetes-ingress-controller/nginx-ingress-controller | yes |
| image_version | Docker image version for the nginx-ingress-controller | string | 0.24.1 | yes|

## Outputs

| Name | Description | Type | Default | Required |
|------|-------------|:----:|:-----:|:-----:|
| None ||||||
