export interface Participant {
  uuid: string
  participant_code: string
  first_name: string
  last_name: string
  email: string | null
  phone?: string | null
  date_of_birth?: string | null
  gender?: string | null
  country?: string | null
  status: string
}

export interface ParticipantImportRowData {
  participant_code: string | null
  first_name: string | null
  last_name: string | null
  email: string | null
  phone: string | null
  date_of_birth: string | null
  gender: string | null
  country: string | null
}

export interface ParticipantImportPreviewRow {
  row: number
  valid: boolean
  duplicate: boolean
  existing: boolean
  existing_participant_uuid: string | null
  data: ParticipantImportRowData
  errors: string[]
}

export interface ParticipantImportPreviewSummary {
  total_rows: number
  valid_rows: number
  invalid_rows: number
  new_participants: number
  existing_participants: number
  duplicates: number
}

export interface ParticipantImportPreview {
  program: {
    uuid: string
    program_code: string
    title: string
  }

  file: {
    name: string
    size: number
    extension: string
  }

  summary: ParticipantImportPreviewSummary

  rows: ParticipantImportPreviewRow[]
}

export interface ParticipantImportCommitRow {
  row: number

  status:
    | 'created'
    | 'updated'
    | 'existing'
    | 'duplicate'
    | 'invalid'

  participant_uuid?: string
  participant_code?: string
  name?: string
  enrolled?: boolean
  errors?: string[]
}

export interface ParticipantImportCommitResult {
  program: {
    uuid: string
    program_code: string
    title: string
  }

  file: {
    name: string
    size: number
    extension: string
  }

  batch_uuid: string

  summary: {
    total_rows: number
    created: number
    updated: number
    enrolled: number
    skipped: number
  }

  rows: ParticipantImportCommitRow[]
}