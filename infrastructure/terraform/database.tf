resource "google_sql_database_instance" "production" {
    database_version              = "MYSQL_8_0"
    name                          = "production8"

    lifecycle {
        prevent_destroy = false
        ignore_changes = [settings[0].disk_size]
    }

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
    password = var.production_sql_password
    host = "%"
    instance = google_sql_database_instance.production.name
}

resource "google_sql_user" "staging" {
    name     = "staging"
    password = var.staging_sql_password
    host = "%"
    instance = google_sql_database_instance.production.name
}

resource "google_sql_user" "qa" {
    name     = "qa"
    password = var.qa_sql_password
    host = "%"
    instance = google_sql_database_instance.production.name
}
