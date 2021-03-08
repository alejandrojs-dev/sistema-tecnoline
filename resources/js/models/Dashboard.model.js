export default class Dashboard {
  constructor(name, description, isInCarousel, active, allowedDepartments, isSlide, isRowData) {
    this.name = name
    this.description = description
    this.isInCarousel = isInCarousel
    this.active = active
    this.allowedDepartments = allowedDepartments
    this.isSlide = isSlide
    this.isRowData = isRowData
  }
}
