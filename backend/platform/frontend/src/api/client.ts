import axios from 'axios'

export const api =
  axios.create({
    baseURL:
      import.meta.env.VITE_API_URL,

    headers: {
      Accept:
        'application/json',
    },
  })

api.interceptors.request.use(
  config => {
    const token =
      localStorage.getItem(
        'awaken_token',
      )

    if (token) {
      config.headers.Authorization =
        `Bearer ${token}`
    }

    const organizationUuid =
      localStorage.getItem(
        'organization_uuid',
      )

    if (organizationUuid) {
      config.headers[
        'X-Organization'
      ] =
        organizationUuid
    }

    /*
     * FormData must NOT use
     * application/json.
     *
     * The browser must generate:
     *
     * multipart/form-data;
     * boundary=...
     */
    if (
      config.data instanceof
      FormData
    ) {
      delete config.headers[
        'Content-Type'
      ]
    } else {
      config.headers[
        'Content-Type'
      ] =
        'application/json'
    }

    return config
  },
)