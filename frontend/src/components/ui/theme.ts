// VIEW COLOR NAME MAPPING IN tailwind.config.js
const primary: string = 'text-white bg-primary-500 hover:bg-primary-600'
const success: string = 'text-white bg-success-500 hover:bg-success-600'
const info: string = 'text-white bg-info-500 hover:bg-info-600'
const danger: string = 'text-white bg-danger-500 hover:bg-danger-600'
const neutral: string = 'text-white bg-neutral-500 hover:bg-neutral-600'
const warning: string = 'text-white bg-warning-500 hover:bg-warning-600'

const buttonPrimary: string = `${primary} focus:ring-primary-400`
const buttonSuccess: string = `${success} focus:ring-success-400`
const buttonInfo: string = `${info} focus:ring-info-400`
const buttonDanger: string = `${danger} focus:ring-danger-400`
const buttonNeutral: string = `${neutral} focus:ring-neutral-400`
const buttonWarning: string = `${warning} focus:ring-warning-400`

export {
  buttonPrimary,
  buttonSuccess,
  buttonInfo,
  buttonDanger,
  buttonNeutral,
  buttonWarning,
}
