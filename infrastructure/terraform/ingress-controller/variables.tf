#quay.io/kubernetes-ingress-controller/nginx-ingress-controller:0.24.1

variable "image" {
  description = "Docker image for the nginx-ingress-controller"
  default = "quay.io/kubernetes-ingress-controller/nginx-ingress-controller"
}

variable "image_version" {
  description = "Docker image version for the nginx-ingress-controller"
  default = "0.24.1"
}
