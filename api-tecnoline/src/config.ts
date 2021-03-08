import dotenv from 'dotenv'

const dotEnvConfig = dotenv.config({ encoding: 'utf-8' })

if (dotEnvConfig.error) console.error(dotEnvConfig.error)

const config = {
  database: {
    name: process.env.DB_DATABASE,
    username: process.env.DB_USERNAME,
    password: process.env.DB_PASSWORD,
    dialect: process.env.DB_CONNECTION,
    host: process.env.DB_HOST,
  },
}
export default config
