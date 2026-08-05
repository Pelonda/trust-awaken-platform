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

import ParticipantForm from './ParticipantForm'
import { useCreateParticipant } from './mutations'

interface Props {
  open: boolean
  onClose: () => void
}

export default function ParticipantDialog({
  open,
  onClose,
}: Props) {
  const queryClient = useQueryClient()

  const mutation = useCreateParticipant()

  const [form, setForm] = useState({
    participant_code: '',
    first_name: '',
    last_name: '',
    email: '',
  })

  function resetForm() {
    setForm({
      participant_code: '',
      first_name: '',
      last_name: '',
      email: '',
    })
  }

  function save() {
    mutation.mutate(
      {
        organization_id: 1,
        ...form,
        status: 'active',
      },
      {
        onSuccess: async () => {

          Toast.success(
            'Participant created successfully.'
          )

          await queryClient.invalidateQueries({
            queryKey: ['participants'],
          })

          resetForm()

          onClose()

        },

        onError: () => {

          Toast.error(
            'Unable to create participant.'
          )

        },

      }
    )
  }

  function close() {
    resetForm()
    onClose()
  }

  return (
    <Dialog
      open={open}
      onClose={close}
      fullWidth
      maxWidth="sm"
    >

      <DialogTitle>
        Create Participant
      </DialogTitle>

      <DialogContent dividers>

        <ParticipantForm
          value={form}
          onChange={setForm}
        />

      </DialogContent>

      <DialogActions>

        <Button onClick={close}>
          Cancel
        </Button>

        <Button
          variant="contained"
          onClick={save}
          disabled={mutation.isPending}
        >
          {mutation.isPending
            ? 'Saving...'
            : 'Save'}
        </Button>

      </DialogActions>

    </Dialog>
  )
}