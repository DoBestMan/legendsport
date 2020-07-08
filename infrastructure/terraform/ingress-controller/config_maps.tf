resource "kubernetes_config_map" "nginx_configuration" {
  metadata {
    name      = "nginx-configuration"
    namespace = kubernetes_namespace.ingress_nginx.metadata.0.name

    labels = {
      "app.kubernetes.io/name"   = local.name
      "app.kubernetes.io/part-of" = local.name
    }
  }
}

resource "kubernetes_config_map" "tcp_services" {
  metadata {
    name      = "tcp-services"
    namespace = kubernetes_namespace.ingress_nginx.metadata.0.name

    labels = {
      "app.kubernetes.io/name"    = local.name
      "app.kubernetes.io/part-of" = local.name
    }
  }
}

resource "kubernetes_config_map" "udp_services" {
  metadata {
    name      = "udp-services"
    namespace = kubernetes_namespace.ingress_nginx.metadata.0.name

    labels = {
      "app.kubernetes.io/name"    = local.name
      "app.kubernetes.io/part-of" = local.name
    }
  }
}
