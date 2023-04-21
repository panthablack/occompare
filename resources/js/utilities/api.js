import axios from 'axios'

const api = (url, options) => {
  return axios(`/api${url}`, options)
}

export default api
