export interface Credential {
  uuid: string

  credential_number: string
  credential_type: string
  status: string

  verification_code?: string

  verification_url?:
    string | null

  document_template_id?:
    number | null

  issued_at?:
    string | null

  expires_at?:
    string | null
}

export interface IssueFabricCredentialPayload {
  participant_uuid: string
  program_uuid: string
  session_uuid: string
  document_template_uuid: string

  credential_type: string

  expires_at?:
    string | null

  metadata?:
    Record<string, unknown> | null
}