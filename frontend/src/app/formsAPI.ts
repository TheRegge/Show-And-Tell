import axios, { Method } from 'axios'
import { IForm } from './model'
import { dayObjectToTimestampString } from './utils/dates'
declare global {
  interface Window {
    satWpApiSettings: {
      nonce: string,
      apiurl: string,
    }
  }
}
const satWpApiSettings = window.satWpApiSettings || {}
const base_url = satWpApiSettings?.apiurl + 'show-and-tell/v1/admin'

/**
 * Get all forms from backend API
 *
 * @export
 * @param {Boolean} [count] if true, returns the number of results only
 * @returns {Promise<any>}
 */
export function getAllForms(count?: boolean): Promise<any> {
  const countParam: String = count ? '1' : '0'
  const fetchUrl: RequestInfo = `${base_url}/forms/?count=${countParam}`
  return doFetch('GET', fetchUrl, { count: countParam })
}

export function createForm(formData: IForm) {
  const fetchUrl: RequestInfo = `${base_url}/form/`
  const dd = formData.disabledate || ''
  let formattedDisabledate = ''
  if (dd) {
    formattedDisabledate = dayObjectToTimestampString(dd)
  }
  return doFetch('POST', fetchUrl, { ...formData, disabledate: formattedDisabledate })
}

export function updateForm(formData: IForm) {
  const fetchUrl: RequestInfo = `${base_url}/form/${formData.id}`
  const dd = formData.disabledate || ''
  let formattedDisabledate = ''
  if (dd) {
    formattedDisabledate = dayObjectToTimestampString(dd)
  }
  return doFetch('POST', fetchUrl, { ...formData, disabledate: formattedDisabledate })
}

export function deleteForm(formId: string) {
  const fetchUrl: RequestInfo = `${base_url}/form/${formId}`
  return doFetch('DELETE', fetchUrl, { id: formId })
}


const doFetch = async (method: Method = "GET", url: string, params: any = {}) => {
  const res = await axios({
    method: method,
    url: url,
    data: params,
    headers: { 'X-WP-Nonce': satWpApiSettings.nonce }
  })

  return res.data
}



export default {
  createForm,
  deleteForm,
  getAllForms,
  updateForm,
}
