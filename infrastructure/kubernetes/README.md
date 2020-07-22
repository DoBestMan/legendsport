Contains (templated) kubernetes manifests for deployment to staging/qa/prod.

Also contains manually applied manifests for shared cluster resources which arent (currently)
possible to manage using terraform. eg cert.manager.yaml these are in the cluster sub directory
(See https://cert-manager.io/docs/installation/kubernetes/)
