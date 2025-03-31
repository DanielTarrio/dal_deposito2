#!/usr/bin/env make
# ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.SILENT:

# ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

PWD:=$(CURDIR)
UID=$(shell id -u)
GID=$(shell id -g)

# environment
ENV_FILE:=$(PWD)/example.env
ifneq ("$(wildcard $(PWD)/.env)","")
	ENV_FILE:=$(PWD)/.env
endif
ifneq ("$(wildcard $(ENV_FILE))","")
	include $(ENV_FILE)
	export sed 's/=.*//' $(ENV_FILE)
endif

# ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

_PRX=\033[38;5;
_RST=\033[m

help:
	command -p grep -E '^(##.*)|([a-zA-Z%\._-]+:.*?##\s.*)$$' $(PWD)/Makefile \
	  | awk 'BEGIN {FS = ":.*?## "} \
	    /^##@/ {printf "\n$(_RST)$(_PRX)123m%s\n", toupper(substr($$0, 4))} \
	    /^##~/ {printf "$(_RST)$(_PRX)159m%s\n",   substr($$0, 3)} \
	    /^##:/ {printf "$(_RST)$(_PRX)195m%s\n",   substr($$0, 4)} \
	    /^##-/ {printf "$(_RST)$(_PRX)237m%s\n",   substr($$0, 3)} \
	    /^##,/ {split(substr($$0, 5),x,"="); printf "$(_RST)$(_PRX)240;3m  - %-10s : %s\n", x[1], x[2]} \
	    /^[a-zA-Z].*/ {printf "$(_RST)$(_PRX)230m %-12s $(_PRX)250m-- %s\n", $$1, $$2 }' 1>&2
	printf 1>&2 '\n$(_RST)'

##@ Kustomize
##~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
##: Arguments:
##, target=Overlay a ejecutar (default: 51-development)
##~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

target?=51-development
overlay?=$(PWD)/overlay/$(target)

k.apply: ## Despliega la infraestructura para SSO
	oc apply -k $(overlay)
k.delete: ## Elimina la infraestructura para SSO
	oc delete -k $(overlay)
k.deploy: delete apply  ## Despliega y despliega la infraestructura
