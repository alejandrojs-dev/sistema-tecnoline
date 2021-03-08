import dotenv, { DotenvConfigOutput } from 'dotenv'

class Enviroment {
  private _dotenv: DotenvConfigOutput

  constructor() {
    this._dotenv = dotenv.config({ encoding: 'utf-8' })
    if (this._dotenv.error) console.error(this._dotenv.error)
  }

  public local(): any {
    return {
      database: {
        name: process.env.DB_DATABASE,
        username: process.env.DB_USERNAME,
        password: process.env.DB_PASSWORD,
        dialect: process.env.DB_CONNECTION,
        host: process.env.DB_HOST,
      },
      socketIO: {
        cors: {
          origin: 'http://tecnoline2.0.test',
          credentials: true,
          methods: ['GET', 'POST', 'PUT', 'DELETE'],
          allowedHeaders: ['Content-type', 'Accept'],
        },
        pingTimeout: 1000,
      },
    }
  }

  public production(): any {
    return {
      database: {
        name: process.env.DB_DATABASE,
        username: process.env.DB_USERNAME,
        password: process.env.DB_PASSWORD,
        dialect: process.env.DB_CONNECTION,
        host: process.env.DB_HOST,
      },
      socketIO: {
        cors: {
          origin: 'http://tecnoline2.0.test',
          credentials: true,
          methods: ['GET', 'POST', 'PUT', 'DELETE'],
          allowedHeaders: ['Content-type', 'Accept'],
        },
        pingTimeout: 1000,
      },
    }
  }
}

export default new Enviroment()
