node:
	docker exec -ti cc_angular_1 /bin/bash
php:
	docker exec -ti cc_api_1 /bin/bash
db:
	docker exec -ti cc_db_1 /bin/bash
require:
	docker-compose run composer bash -c "composer require $(package)"
cdn:
	docker exec -ti cc_cdn_1 /bin/bash

.PHONY: all db clean
.PHONY: all cdn clean
