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

import OrganizationForm from './OrganizationForm'
import { useCreateOrganization } from './mutations'

interface Props {
  open: boolean
  onClose: () => void
}

export default function OrganizationDialog({
  open,
  onClose,
}: Props) {
  const queryClient = useQueryClient()

  const mutation = useCreateOrganization()

  const [form, setForm] = useState({
    display_name: '',
    legal_name: '',
    organization_type: 'nonprofit',
  })

  function reset() {
    setForm({
      display_name: '',
      legal_name: '',
      organization_type: 'nonprofit',
    })
  }

  function save() {
    mutation.mutate(
      {
        ...form,
        owner_user_id: 1,
      },
      {
        onSuccess: async () => {
          Toast.success(
            'Organization created successfully.'
          )

          await queryClient.invalidateQueries({
            queryKey: ['organizations'],
          })

          reset()

          onClose()
        },

        onError: () => {
          Toast.error(
            'Unable to create organization.'
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
        Create Organization
      </DialogTitle>

      <DialogContent dividers>

        <OrganizationForm
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