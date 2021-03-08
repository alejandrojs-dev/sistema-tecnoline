import { Request, Response } from 'express'
import { HttpStatusCodes } from '../utils/HttpStatusCodeEnum'
import { dashboardController } from '../inversify.config'
import ErrorHandler from '../utils/ErrorHandler'

const getDashboardData = async (req: Request, res: Response, next: any): Promise<any> => {
  try {
    const { id } = req.params
    const response = await dashboardController.getDashboardData(parseInt(id))

    return res.json({
      ok: true,
      message: 'Si funciona',
      statusCode: HttpStatusCodes.OK,
      data: response,
    })
  } catch (error) {
    next(new ErrorHandler(HttpStatusCodes.INTERNAL_SERVER_ERROR, error.message))
  }
}

export { getDashboardData }
