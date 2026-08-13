import { api } from '../../api/client'

import type {
  Participant,
  ParticipantImportCommitResult,
  ParticipantImportPreview,
} from './types'

export async function getParticipants() {
  const { data } =
    await api.get<{
      data: Participant[]
    }>('/participants')

  return data
}

export async function createParticipant(
  payload: unknown,
) {
  const { data } =
    await api.post(
      '/participants',
      payload,
    )

  return data
}

export async function updateParticipant(
  uuid: string,
  payload: unknown,
) {
  const { data } =
    await api.put(
      `/participants/${uuid}`,
      payload,
    )

  return data
}

export async function deleteParticipant(
  uuid: string,
) {
  const { data } =
    await api.delete(
      `/participants/${uuid}`,
    )

  return data
}

/*
|--------------------------------------------------------------------------
| Participant Import Preview
|--------------------------------------------------------------------------
*/

export async function previewParticipantImport(
  file: File,
  programUuid: string,
): Promise<ParticipantImportPreview> {
  const formData =
    new FormData()

  formData.append(
    'file',
    file,
  )

  formData.append(
    'program_uuid',
    programUuid,
  )

  const { data } =
    await api.post<{
      data: ParticipantImportPreview
    }>(
      '/participants/import/preview',
      formData,
    )

  return data.data
}

/*
|--------------------------------------------------------------------------
| Participant Import Commit
|--------------------------------------------------------------------------
*/

export async function commitParticipantImport(
  file: File,
  programUuid: string,
  options?: {
    updateExisting?: boolean
    enrollmentStatus?:
      | 'enrolled'
      | 'active'
      | 'completed'
  },
): Promise<ParticipantImportCommitResult> {
  const formData =
    new FormData()

  formData.append(
    'file',
    file,
  )

  formData.append(
    'program_uuid',
    programUuid,
  )

  formData.append(
    'update_existing',
    options?.updateExisting === false
      ? '0'
      : '1',
  )

  formData.append(
    'enrollment_status',
    options?.enrollmentStatus ??
      'enrolled',
  )

  const { data } =
    await api.post<{
      data: ParticipantImportCommitResult
    }>(
      '/participants/import/commit',
      formData,
    )

  return data.data
}