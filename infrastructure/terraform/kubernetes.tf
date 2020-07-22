provider "kubernetes" {
    load_config_file = false

    host  = "https://${google_container_cluster.primary.endpoint}"
    token = data.google_client_config.provider.access_token
    cluster_ca_certificate = base64decode(
      google_container_cluster.primary.master_auth[0].cluster_ca_certificate,
    )
}

provider "kubernetes-alpha" {
    load_config_file = false

    host  = "https://${google_container_cluster.primary.endpoint}"
    token = data.google_client_config.provider.access_token
    cluster_ca_certificate = base64decode(
      google_container_cluster.primary.master_auth[0].cluster_ca_certificate,
    )
}

resource "kubernetes_namespace" "qa" {
    metadata {
        name = "qa"
    }
}

resource "kubernetes_namespace" "staging" {
    metadata {
        name = "staging"
    }
}

resource "kubernetes_namespace" "production" {
    metadata {
        name = "production"
    }
}

resource "kubernetes_secret" "qa_sql_credentials" {

    metadata {
        namespace = kubernetes_namespace.qa.metadata[0].name
        name = "db-credentials"
    }

    data = {
        username = google_sql_user.qa.name
        password = google_sql_user.qa.password
    }
}

resource "kubernetes_secret" "staging_sql_credentials" {

    metadata {
        namespace = kubernetes_namespace.staging.metadata[0].name
        name = "db-credentials"
    }

    data = {
        username = google_sql_user.staging.name
        password = google_sql_user.staging.password
    }
}

resource "kubernetes_secret" "production_sql_credentials" {

    metadata {
        namespace = kubernetes_namespace.production.metadata[0].name
        name = "db-credentials"
    }

    data = {
        username = google_sql_user.production.name
        password = google_sql_user.production.password
    }
}

module "nginx-ingress-controller" {
    source = "./ingress-controller"
    loadbalancer_ip_address = google_compute_address.production.address
}
