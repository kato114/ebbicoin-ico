module.exports = {
  /**
   * Application configuration section
   * http://pm2.keymetrics.io/docs/usage/application-declaration/
   */
  apps: [
    // First application
    {
      name: "ebbicoin-api",
      instances: "max",
      exec_mode: "cluster",
      script: "server.js",
      env: {
        COMMON_VARIABLE: "true"
      },
      env_production: {
        NODE_ENV: "production"
      }
    }

    // Second application
    // {
    //   name      : 'WEB',
    //   script    : 'web.js'
    // }
  ],

  /**
   * Deployment section
   * http://pm2.keymetrics.io/docs/usage/deployment/
   */
  deploy: {
    production: {
      key: "~/.ssh/ebbicoin.pem",
      user: "centos",
      host: "18.233.113.38",
      ref: "origin/master",
      repo: "git@github.com:vstlouis/ebbicoin.git",
      path: "/var/www/ebbicoin",
      "pre-deploy": "git pull",
      "post-deploy":
        "cd ./frontend && npm install && npm run build && cp -a dist/* ../. && cd ../api && npm install && pm2 reload ecosystem.config.js --env production"
    }
  }
};
