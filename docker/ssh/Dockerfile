FROM alpine:latest

LABEL maintainer="paco@pacoorozco.info"

# Arguments defined in docker-compose.yml
ARG USER=admin
ARG PASSWORD=docker

RUN apk add --update --no-cache openssh
RUN adduser -h /home/${USER} -s /bin/sh -D ${USER} && \
    echo "${USER}:${PASSWORD}" | chpasswd
COPY --chown=${USER}:${USER} ./authorized_keys /home/${USER}/.ssh/authorized_keys

# Configures SSH to enable SFTP service.
COPY ./sshd/*.conf /etc/ssh/sshd_config.d/
RUN sed -i "s/__DOCKER_USER__/${USER}/" /etc/ssh/sshd_config.d/sftp.conf

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 22

ENTRYPOINT ["/entrypoint.sh"]
