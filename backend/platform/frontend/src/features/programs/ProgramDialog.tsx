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

import ProgramForm from './ProgramForm'
import { useCreateProgram } from './mutations'

interface Props {
  open: boolean
  onClose: () => void
}

export default function ProgramDialog({
  open,
  onClose,
}: Props) {
  const queryClient = useQueryClient()

  const mutation = useCreateProgram()

  const [form, setForm] = useState({
    program_code: '',
    title: '',
    program_type: '',
    delivery_mode: '',
  })

  function resetForm() {
    setForm({
      program_code: '',
      title: '',
      program_type: '',
      delivery_mode: '',
    })
  }

  function save() {
    mutation.mutate(
      {
        organization_id: 1,
        ...form,
        status: 'draft',
        language: 'en',
        credential_enabled: true,
      },
      {
        onSuccess: async () => {
          Toast.success(
            'Program created successfully.'
          )

          await queryClient.invalidateQueries({
            queryKey: ['programs'],
          })

          resetForm()

          onClose()
        },

        onError: () => {
          Toast.error(
            'Unable to create program.'
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
        Create Program
      </DialogTitle>

      <DialogContent dividers>

        <ProgramForm
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