#!/bin/sh
set -eu
# Syntax-check the reference configuration with an ephemeral certificate and root.
# This does not start a server or validate a real domain/certificate.
root="$(pwd)"
fixture="$root/dist/nginx-syntax"
mkdir -p "$fixture"
openssl req -x509 -newkey rsa:2048 -nodes -days 1 -subj '/CN=shop.example.com' \
  -keyout "$fixture/privkey.pem" -out "$fixture/fullchain.pem" >/dev/null 2>&1
sed -e "s|/etc/letsencrypt/live/shop.example.com|$fixture|g" \
    -e "s|/srv/ecoshop/current/public|$root/public|g" deploy/nginx.conf > "$fixture/site.conf"
cat > "$fixture/nginx.conf" <<EOF
pid $fixture/nginx.pid;
error_log stderr;
events {}
http {
    include /etc/nginx/mime.types;
    access_log off;
    client_body_temp_path $fixture/body;
    fastcgi_temp_path $fixture/fastcgi;
    include $fixture/site.conf;
}
EOF
nginx -t -c "$fixture/nginx.conf"
