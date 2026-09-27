FROM nginxinc/nginx-unprivileged:1.29-alpine

ARG VERSION=dev
ARG REVISION=unknown
ARG CREATED

LABEL org.opencontainers.image.title="cben.dev" \
      org.opencontainers.image.description="Personal website of Christian Rodriguez Benthake" \
      org.opencontainers.image.url="https://cben.dev" \
      org.opencontainers.image.source="https://github.com/ChrisB9/ChrisB9" \
      org.opencontainers.image.licenses="MIT" \
      org.opencontainers.image.version="${VERSION}" \
      org.opencontainers.image.revision="${REVISION}" \
      org.opencontainers.image.created="${CREATED}"

COPY deploy/nginx.conf /etc/nginx/conf.d/default.conf
COPY dist/ /usr/share/nginx/html/

EXPOSE 8080

HEALTHCHECK --interval=30s --timeout=3s --start-period=5s \
    CMD wget -qO- http://127.0.0.1:8080/healthz || exit 1
