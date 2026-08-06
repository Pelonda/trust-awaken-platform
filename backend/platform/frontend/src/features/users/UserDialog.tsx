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

import UserForm from './UserForm'
import { useCreateUser } from './mutations'

interface Props {
  open: boolean
  onClose: () => void
}

export default function UserDialog({
  open,
  onClose,
}: Props) {

  const queryClient = useQueryClient()

  const mutation = useCreateUser()

  const [form, setForm] = useState({
    name: '',
    email: '',
    password: '',
    phone: '',
    job_title: '',
    user_type: 'organization',
    status: 'active',
  })

  function reset() {
    setForm({
      name: '',
      email: '',
      password: '',
      phone: '',
      job_title: '',
      user_type: 'organization',
      status: 'active',
    })
  }

  function save() {

    mutation.mutate(
      form,
      {
        onSuccess: async () => {

          Toast.success(
            'User created successfully.'
          )

          await queryClient.invalidateQueries({
            queryKey: ['users'],
          })

          reset()

          onClose()

        },

        onError: () => {

          Toast.error(
            'Unable to create user.'
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
      maxWidth="md"
    >

      <DialogTitle>
        Create User
      </DialogTitle>

      <DialogContent dividers>

        <UserForm
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