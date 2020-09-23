The contents of this directory need manually applying as Terraform cannot connect to the
private sql instance without significant extra work.

`kubectl run mysql --rm -i --tty --image mysql -- mysql -h 10.35.48.7 -u root -p`

`kubectl attach mysql -c mysql -i -t`
