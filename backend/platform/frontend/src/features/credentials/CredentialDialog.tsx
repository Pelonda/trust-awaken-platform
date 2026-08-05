import {
  Button,
  Dialog,
  DialogActions,
  DialogContent,
  DialogTitle,
} from '@mui/material'

import { useState } from 'react'
import { useQueryClient } from '@tanstack/react-query'

import * as Toast from '../../components/common/AppToast'

import CredentialForm from './CredentialForm'
import { useCreateCredential } from './mutations'

interface Props {
  open: boolean
  onClose: () => void
}

export default function CredentialDialog({
  open,
  onClose,
}: Props) {

  const queryClient = useQueryClient()

  const mutation = useCreateCredential()

  const [form, setForm] = useState({
    participant_id: '',
    program_id: '',
    credential_type: 'certificate',
  })

  function reset() {
    setForm({
      participant_id: '',
      program_id: '',
      credential_type: 'certificate',
    })
  }

  function save() {

    mutation.mutate(
      {
        organization_id: 1,
        session_id: 1,
        template_id: 1,
        ...form,
      },
      {
        onSuccess: async () => {

          Toast.success(
            'Credential created successfully.'
          )

          await queryClient.invalidateQueries({
            queryKey: ['credentials'],
          })

          reset()

          onClose()

        },

        onError: () => {

          Toast.error(
            'Unable to create credential.'
          )

        },

      }
    )

  }

  return (
    <Dialog
      open={open}
      onClose={onClose}
      fullWidth
      maxWidth="sm"
    >

      <DialogTitle>
        Issue Credential
      </DialogTitle>

      <DialogContent dividers>

        <CredentialForm
          value={form}
          onChange={setForm}
        />

      </DialogContent>

      <DialogActions>

        <Button onClick={onClose}>
          Cancel
        </Button>

        <Button
          variant="contained"
          onClick={save}
          disabled={mutation.isPending}
        >
          {mutation.isPending
            ? 'Saving...'
            : 'Issue'}
        </Button>

      </DialogActions>

    </Dialog>
  )
}