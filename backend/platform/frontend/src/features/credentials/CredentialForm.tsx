import {
  Alert,
  CircularProgress,
  MenuItem,
  Stack,
  TextField,
  Typography,
} from '@mui/material'

import {
  useQuery,
} from '@tanstack/react-query'

import {
  useParticipants,
} from '../participants/hooks'

import {
  usePrograms,
} from '../programs/hooks'

import {
  useSessions,
} from '../sessions/hooks'

import FabricTemplateService from
  '../documents/services/FabricTemplateService'

export interface CredentialFormData {
  participant_uuid: string
  program_uuid: string
  session_uuid: string

  document_template_uuid:
    string

  credential_type: string
  expires_at: string
}

interface Props {
  value:
    CredentialFormData

  onChange: (
    value:
      CredentialFormData,
  ) => void
}

export default function CredentialForm({
  value,
  onChange,
}: Props) {
  const participantsQuery =
    useParticipants()

  const programsQuery =
    usePrograms()

  const sessionsQuery =
    useSessions()

  const templatesQuery =
    useQuery({
      queryKey: [
        'fabric-document-templates',
        localStorage.getItem(
          'organization_uuid',
        ),
      ],

      queryFn: () =>
        FabricTemplateService.all(),
    })

  const participants =
    participantsQuery
      .data
      ?.data ??
    []

  const programs =
    programsQuery
      .data
      ?.data ??
    []

  const sessions =
    sessionsQuery
      .data
      ?.data ??
    []

  const templates =
    templatesQuery.data ??
    []

  const loading =
    participantsQuery.isLoading ||
    programsQuery.isLoading ||
    sessionsQuery.isLoading ||
    templatesQuery.isLoading

  const loadError =
    participantsQuery.isError ||
    programsQuery.isError ||
    sessionsQuery.isError ||
    templatesQuery.isError

  function update<
    K extends
      keyof CredentialFormData,
  >(
    field: K,
    fieldValue:
      CredentialFormData[K],
  ) {
    onChange({
      ...value,

      [field]:
        fieldValue,
    })
  }

  if (loading) {
    return (
      <Stack
        alignItems="center"
        spacing={2}
        sx={{
          py: 4,
        }}
      >
        <CircularProgress />

        <Typography
          variant="body2"
          color="text.secondary"
        >
          Loading issuance data...
        </Typography>
      </Stack>
    )
  }

  if (loadError) {
    return (
      <Alert severity="error">
        Unable to load the data
        required to issue a
        credential.
      </Alert>
    )
  }

  return (
    <Stack spacing={3}>
      <TextField
        select
        fullWidth
        required
        label="Participant"
        value={
          value.participant_uuid
        }
        onChange={
          event =>
            update(
              'participant_uuid',
              event.target.value,
            )
        }
        helperText={
          participants.length === 0
            ? 'No participants are available for this organization.'
            : 'Select the credential recipient.'
        }
      >
        {participants.map(
          participant => (
            <MenuItem
              key={
                participant.uuid
              }
              value={
                participant.uuid
              }
            >
              {[
                participant.first_name,
                participant.last_name,
              ]
                .filter(Boolean)
                .join(' ')}

              {' — '}

              {
                participant
                  .participant_code
              }
            </MenuItem>
          ),
        )}
      </TextField>

      <TextField
        select
        fullWidth
        required
        label="Program"
        value={
          value.program_uuid
        }
        onChange={
          event => {
            onChange({
              ...value,

              program_uuid:
                event.target.value,

              /*
               * A session belongs
               * to a program.
               */
              session_uuid:
                '',
            })
          }
        }
        helperText={
          programs.length === 0
            ? 'No programs are available for this organization.'
            : 'Select the program that earned this credential.'
        }
      >
        {programs.map(
          program => (
            <MenuItem
              key={
                program.uuid
              }
              value={
                program.uuid
              }
            >
              {program.title}

              {' — '}

              {
                program
                  .program_code
              }
            </MenuItem>
          ),
        )}
      </TextField>

      <TextField
        select
        fullWidth
        required
        label="Session"
        value={
          value.session_uuid
        }
        disabled={
          !value.program_uuid
        }
        onChange={
          event =>
            update(
              'session_uuid',
              event.target.value,
            )
        }
        helperText={
          !value.program_uuid
            ? 'Select a program first.'
            : sessions.length === 0
              ? 'No sessions are available.'
              : 'Select the program session.'
        }
      >
        {sessions.map(
          session => (
            <MenuItem
              key={
                session.uuid
              }
              value={
                session.uuid
              }
            >
              {session.title}

              {' — '}

              {
                session
                  .session_code
              }
            </MenuItem>
          ),
        )}
      </TextField>

      <TextField
        select
        fullWidth
        required
        label="Fabric Template"
        value={
          value
            .document_template_uuid
        }
        onChange={
          event =>
            update(
              'document_template_uuid',
              event.target.value,
            )
        }
        helperText={
          templates.length === 0
            ? 'No Fabric templates are available. Create one in Document Studio first.'
            : 'The selected design will be snapshotted when this credential is issued.'
        }
      >
        {templates.map(
          template => (
            <MenuItem
              key={
                template.uuid
              }
              value={
                template.uuid
              }
            >
              {template.name}

              {template.default
                ? ' — Default'
                : ''}
            </MenuItem>
          ),
        )}
      </TextField>

      <TextField
        select
        fullWidth
        required
        label="Credential Type"
        value={
          value.credential_type
        }
        onChange={
          event =>
            update(
              'credential_type',
              event.target.value,
            )
        }
      >
        <MenuItem
          value="certificate"
        >
          Certificate
        </MenuItem>

        <MenuItem
          value="badge"
        >
          Badge
        </MenuItem>
      </TextField>

      <TextField
        fullWidth
        type="date"
        label="Expiry Date"
        value={
          value.expires_at
        }
        onChange={
          event =>
            update(
              'expires_at',
              event.target.value,
            )
        }
        slotProps={{
          inputLabel: {
            shrink:
              true,
          },
        }}
        helperText="Optional. Leave blank for a credential with no expiry date."
      />
    </Stack>
  )
}