import { api } from '../../api/client'

import type {
  Credential,
  IssueFabricCredentialPayload,
} from './types'

export async function getCredentials() {
  const { data } =
    await api.get<{
      data: Credential[]
    }>(
      '/credentials',
    )

  return data
}

export async function createCredential(
  payload: unknown,
) {
  const { data } =
    await api.post(
      '/credentials',
      payload,
    )

  return data
}

export async function issueFabricCredential(
  payload:
    IssueFabricCredentialPayload,
) {
  const { data } =
    await api.post(
      '/credentials/issue-fabric',
      payload,
    )

  return data
}

export async function updateCredential(
  uuid: string,
  payload: unknown,
) {
  const { data } =
    await api.put(
      `/credentials/${uuid}`,
      payload,
    )

  return data
}

/*
|--------------------------------------------------------------------------
| Revoke / Restore
|--------------------------------------------------------------------------
*/

export async function revokeCredential(
  uuid: string,
) {
  const { data } =
    await api.post(
      `/credentials/${uuid}/revoke`,
    )

  return data
}

export async function restoreCredential(
  uuid: string,
) {
  const { data } =
    await api.post(
      `/credentials/${uuid}/restore`,
    )

  return data
}

/*
|--------------------------------------------------------------------------
| Preview
|--------------------------------------------------------------------------
|
| Do not use window.open(API_URL).
|
| The API requires the Axios client's authentication and organization
| headers. Fetch the PDF through Axios first, then open a local Blob URL.
|
*/

export async function previewCredential(
  uuid: string,
) {
  const response =
    await api.get(
      `/credentials/${uuid}/preview`,
      {
        responseType:
          'blob',
      },
    )

  const blob =
    new Blob(
      [
        response.data,
      ],
      {
        type:
          'application/pdf',
      },
    )

  const url =
    window.URL.createObjectURL(
      blob,
    )

  const previewWindow =
    window.open(
      url,
      '_blank',
    )

  if (!previewWindow) {
    window.URL.revokeObjectURL(
      url,
    )

    throw new Error(
      'The browser blocked the credential preview window.',
    )
  }

  /*
   * Do not revoke immediately.
   *
   * The new tab still needs the Blob URL.
   */
  window.setTimeout(
    () => {
      window.URL.revokeObjectURL(
        url,
      )
    },
    60_000,
  )
}

/*
|--------------------------------------------------------------------------
| Download
|--------------------------------------------------------------------------
*/

export async function downloadCredential(
  uuid: string,
  credentialNumber?: string,
) {
  const response =
    await api.get(
      `/credentials/${uuid}/download`,
      {
        responseType:
          'blob',
      },
    )

  const blob =
    new Blob(
      [
        response.data,
      ],
      {
        type:
          'application/pdf',
      },
    )

  const url =
    window.URL.createObjectURL(
      blob,
    )

  const link =
    document.createElement(
      'a',
    )

  link.href =
    url

  link.download =
    credentialNumber
      ? `${credentialNumber}.pdf`
      : `credential-${uuid}.pdf`

  document.body.appendChild(
    link,
  )

  link.click()

  link.remove()

  window.URL.revokeObjectURL(
    url,
  )
}