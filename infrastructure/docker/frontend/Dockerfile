# syntax=docker/dockerfile:1.0-experimental
FROM node:14.4.0-alpine3.12 as build

RUN mkdir /project

WORKDIR /project

ADD . /project

RUN yarn install --frozen-lockfile --production=true
RUN yarn prod

# alpine 3.11
FROM nginx:1.19-alpine

COPY --chown=nginx:nginx --from=build /project/public /usr/share/nginx/html


