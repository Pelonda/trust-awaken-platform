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
  participant_id: number
  program_id: number
  session_id: number
  document_template_id: number

  credential_type: string

  expires_at?:
    string | null

  metadata?:
    Record<string, unknown> | null
}