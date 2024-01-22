FROM alpine:latest

LABEL maintainer="paco@pacoorozco.info"

# Arguments defined in docker-compose.yml
ARG USER=admin
ARG PASSWORD=docker

RUN apk add --update --no-cache openssh
RUN adduser -h /home/${USER} -s /bin/sh -D ${USER} && \
    chmod 0755 /home/${USER} && \
    echo "${USER}:${PASSWORD}" | chpasswd
COPY --chown=${USER}:${USER} ./authorized_keys /home/${USER}/.ssh/authorized_keys

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 22

ENTRYPOINT ["/entrypoint.sh"]
