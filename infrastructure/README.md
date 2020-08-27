Infrastructure is /mostly/ controlled using terraform.

IAM permissions are created manually at the moment.

Database permissions are also created manually as connecting to mysql from terraform
is not an easy task (see mysql directory)

The lets encrypt certificate manager is also applied to kubernetes manually. See
kubernetes/cluster for the files.

build directory contains build files for qa, staging and prod.

staging and prod are single static environments, qa builds a new environment per
git branch triggered by a PR. At the moment manual cleanup is required of these 
environments using the teardown.sh script. staging is built by commits to master, 
production is built from new tags.

