versions := 7.1 7.2 7.3

.PHONY: $(versions)
$(versions):
	docker build -f .docker/Dockerfile-$@ .

.PHONY: test
test: $(versions) ;
