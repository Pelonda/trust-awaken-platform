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

import SessionForm from './SessionForm'
import { useCreateSession } from './mutations'

interface Props {
  open: boolean
  onClose: () => void
}

export default function SessionDialog({
  open,
  onClose,
}: Props) {

  const queryClient = useQueryClient()

  const mutation = useCreateSession()

  const [form, setForm] = useState({
    session_code: '',
    title: '',
    session_number: 1,
  })

  function reset() {
    setForm({
      session_code: '',
      title: '',
      session_number: 1,
    })
  }

  function save() {

    mutation.mutate(
      {
        program_id: 1,
        ...form,
        starts_at: new Date().toISOString(),
        ends_at: new Date().toISOString(),
        status: 'scheduled',
      },
      {
        onSuccess: async () => {

          Toast.success(
            'Session created successfully.'
          )

          await queryClient.invalidateQueries({
            queryKey: ['sessions'],
          })

          reset()

          onClose()

        },

        onError: () => {

          Toast.error(
            'Unable to create session.'
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
        Create Session
      </DialogTitle>

      <DialogContent dividers>

        <SessionForm
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
            : 'Save'}
        </Button>

      </DialogActions>

    </Dialog>
  )
}