provider "google" {
    credentials = file("account.json")
    project     = "legend-sports-production"
    region      = "us-central1"
}

provider "google-beta" {
    credentials = file("account.json")
    project     = "legend-sports-production"
    region      = "us-central1"
}

data "google_client_config" "provider" {}

resource "google_container_cluster" "primary" {
    name     = "primary"
    location = "us-central1"

    min_master_version = "1.16.9-gke.6"
    # We can't create a cluster with no node pool defined, but we want to only use
    # separately managed node pools. So we create the smallest possible default
    # node pool and immediately delete it.
    remove_default_node_pool = true
    initial_node_count       = 1

    master_auth {
        username = ""
        password = ""

        client_certificate_config {
            issue_client_certificate = false
        }
    }

    network    = "default"
    subnetwork = "default"

    ip_allocation_policy {
        cluster_ipv4_cidr_block  = "/16"
        services_ipv4_cidr_block = "/22"
    }

    default_max_pods_per_node = 110

    addons_config {
        horizontal_pod_autoscaling {
            disabled = false
        }
        http_load_balancing {
            disabled = false
        }
    }

    enable_shielded_nodes = true
}

resource "google_container_node_pool" "primary_nodes" {
    name       = "my-node-pool"
    location   = "us-central1"
    cluster    = google_container_cluster.primary.name
    node_count = 1

    management {
        auto_repair = true
        auto_upgrade = true
    }

    upgrade_settings {
        max_surge = 1
        max_unavailable = 0
    }

    node_config {
        preemptible  = false
        machine_type = "n1-standard-1"
        disk_size_gb = 100
        disk_type = "pd-standard"
        image_type = "COS"

        metadata = {
            disable-legacy-endpoints = "true"
        }

        oauth_scopes = [
            "https://www.googleapis.com/auth/devstorage.read_only",
            "https://www.googleapis.com/auth/logging.write",
            "https://www.googleapis.com/auth/monitoring",
            "https://www.googleapis.com/auth/servicecontrol",
            "https://www.googleapis.com/auth/service.management.readonly",
            "https://www.googleapis.com/auth/trace.append"
        ]

        shielded_instance_config {
            enable_secure_boot = true
        }
    }
}

resource "google_compute_global_address" "production" {
    name = "production"
}

resource "google_sql_database_instance" "production" {
    database_version              = "MYSQL_5_7"
    name                          = "production"

    settings {
        disk_autoresize             = true
        disk_size                   = 10
        disk_type                   = "PD_SSD"
        tier                        = "db-n1-standard-1"

        backup_configuration {
            binary_log_enabled = true
            enabled            = true
            start_time         = "18:00"
        }

        ip_configuration {
            ipv4_enabled    = false
            private_network = "projects/legend-sports-production/global/networks/default"
            require_ssl     = false
        }

        location_preference {
            zone = "us-central1-a"
        }
    }
    timeouts {}
}

resource "google_sql_database" "production" {
    name     = "legendsports-prod"
    instance = google_sql_database_instance.production.name
}

resource "google_sql_database" "staging" {
    name     = "legendsports-staging"
    instance = google_sql_database_instance.production.name
}

resource "google_sql_user" "production" {
    name     = "production"
    host = "%"
    instance = google_sql_database_instance.production.name
}

resource "google_sql_user" "staging" {
    name     = "staging"
    host = "%"
    instance = google_sql_database_instance.production.name
}

resource "google_cloudbuild_trigger" "build-prs" {
    provider = google-beta
    disabled       = false
    filename       = "infrastructure/build/cloudbuild.yaml"
    ignored_files  = []
    included_files = []
    name           = "Build-PRs"
    substitutions  = {}

    github {
        owner = "legends-sports"
        name = "legendsports"
        pull_request {
            branch = "^master$"
        }
    }
}

resource "google_cloudbuild_trigger" "build-staging" {
    provider = google-beta
    disabled       = false
    filename       = "infrastructure/build/cloudbuild-staging.yaml"
    ignored_files  = []
    included_files = []
    name           = "Build-Staging"
    substitutions  = {}

    github {
        owner = "legends-sports"
        name = "legendsports"
        push {
            branch = "^master$"
        }
    }
}

resource "google_cloudbuild_trigger" "build-production" {
    provider = google-beta
    disabled       = false
    filename       = "infrastructure/build/cloudbuild-production.yaml"
    ignored_files  = []
    included_files = []
    name           = "Build-Production"
    substitutions  = {}

    github {
        owner = "legends-sports"
        name = "legendsports"
        push {
            tag = ".*"
        }
    }
}
