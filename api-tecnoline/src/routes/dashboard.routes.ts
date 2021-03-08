import express from 'express'
import { getDashboardData } from '../callbacks/dashboards.callbacks'

const router = express.Router()

router.get('/data/:id', getDashboardData)

export default router
