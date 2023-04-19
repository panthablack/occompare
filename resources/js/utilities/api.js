import axios from 'axios'

const api = (url, options) => {
  return axios(url, options)
}

export default api
