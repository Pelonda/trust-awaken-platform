import {
  Alert,
  Button,
  CircularProgress,
  Dialog,
  DialogActions,
  DialogContent,
  DialogTitle,
  Stack,
  Typography,
} from '@mui/material'

import {
  useState,
} from 'react'

import {
  useQueryClient,
} from '@tanstack/react-query'

import * as Toast
  from '../../components/common/AppToast'

import CredentialForm, {
  type CredentialFormData,
} from './CredentialForm'

import {
  useIssueFabricCredential,
} from './mutations'

interface Props {
  open: boolean
  onClose: () => void
}

const INITIAL_FORM:
  CredentialFormData = {
    participant_uuid: '',
    program_uuid: '',
    session_uuid: '',
    document_template_uuid: '',
    credential_type: 'certificate',
    expires_at: '',
  }

export default function CredentialDialog({
  open,
  onClose,
}: Props) {
  const queryClient =
    useQueryClient()

  const mutation =
    useIssueFabricCredential()

  const [
    form,
    setForm,
  ] =
    useState<CredentialFormData>(
      INITIAL_FORM,
    )

  const [
    formError,
    setFormError,
  ] =
    useState<string | null>(
      null,
    )

  function reset() {
    setForm({
      ...INITIAL_FORM,
    })

    setFormError(
      null,
    )
  }

  function close() {
    if (
      mutation.isPending
    ) {
      return
    }

    reset()

    onClose()
  }

  function validate():
    string | null {
    if (
      !form.participant_uuid
    ) {
      return 'Select a participant.'
    }

    if (
      !form.program_uuid
    ) {
      return 'Select a program.'
    }

    if (
      !form.session_uuid
    ) {
      return 'Select a session.'
    }

    if (
      !form.document_template_uuid
    ) {
      return 'Select a Fabric template.'
    }

    if (
      !form.credential_type
    ) {
      return 'Select a credential type.'
    }

    return null
  }

  function issue() {
    const validationError =
      validate()

    if (validationError) {
      setFormError(
        validationError,
      )

      return
    }

    setFormError(
      null,
    )

    mutation.mutate(
      {
        participant_uuid:
          form.participant_uuid,

        program_uuid:
          form.program_uuid,

        session_uuid:
          form.session_uuid,

        document_template_uuid:
          form.document_template_uuid,

        credential_type:
          form.credential_type,

        expires_at:
          form.expires_at
            ? form.expires_at
            : null,

        metadata: {
          issuance_engine:
            'fabric',
        },
      },
      {
        onSuccess:
          async response => {
            Toast.success(
              'Credential issued successfully.',
            )

            await queryClient
              .invalidateQueries({
                queryKey: [
                  'credentials',
                ],
              })

            console.log(
              'Fabric credential issued:',
              response,
            )

            reset()

            onClose()
          },

        onError:
          (error: any) => {
            console.error(
              'Unable to issue Fabric credential:',
              error,
            )

            const message =
              error?.response
                ?.data
                ?.message ??
              error?.message ??
              'Unable to issue credential.'

            setFormError(
              message,
            )

            Toast.error(
              message,
            )
          },
      },
    )
  }

  return (
    <Dialog
      open={open}
      onClose={close}
      fullWidth
      maxWidth="sm"
    >
      <DialogTitle>
        <Stack spacing={0.5}>
          <Typography
            variant="h6"
            fontWeight={700}
          >
            Issue Credential
          </Typography>

          <Typography
            variant="body2"
            color="text.secondary"
          >
            Issue a verified credential
            using a Fabric Studio template.
          </Typography>
        </Stack>
      </DialogTitle>

      <DialogContent dividers>
        {formError && (
          <Alert
            severity="error"
            sx={{
              mb: 2,
            }}
          >
            {formError}
          </Alert>
        )}

        <CredentialForm
          value={form}
          onChange={value => {
            setForm(
              value,
            )

            if (formError) {
              setFormError(
                null,
              )
            }
          }}
        />
      </DialogContent>

      <DialogActions>
        <Button
          onClick={close}
          disabled={
            mutation.isPending
          }
        >
          Cancel
        </Button>

        <Button
          variant="contained"
          disabled={
            mutation.isPending
          }
          onClick={
            issue
          }
          startIcon={
            mutation.isPending
              ? (
                  <CircularProgress
                    size={16}
                    color="inherit"
                  />
                )
              : undefined
          }
        >
          {mutation.isPending
            ? 'Issuing...'
            : 'Issue Credential'}
        </Button>
      </DialogActions>
    </Dialog>
  )
}