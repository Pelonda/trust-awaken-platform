import { useMemo, useState } from 'react'
import { useMutation, useQueryClient } from '@tanstack/react-query'
import type { GridColDef } from '@mui/x-data-grid'

import AppToolbar from '../../components/common/AppToolbar'
import AppSkeleton from '../../components/common/AppSkeleton'
import EmptyState from '../../components/common/EmptyState'
import SearchBar from '../../components/common/SearchBar'
import ConfirmDialog from '../../components/dialogs/ConfirmDialog'
import AppDataGrid from '../../components/tables/AppDataGrid'
import * as Toast from '../../components/common/AppToast'

import { deleteParticipant } from './api'
import { useParticipants } from './hooks'
import ParticipantDialog from './ParticipantDialog'

export default function ParticipantsPage() {

  const queryClient = useQueryClient()

  const { data, isLoading } = useParticipants()

  const [search, setSearch] = useState('')
  const [createOpen, setCreateOpen] = useState(false)
  const [confirmOpen, setConfirmOpen] = useState(false)
  const [selectedParticipant, setSelectedParticipant] = useState<any>(null)

  const deleteMutation = useMutation({

    mutationFn: deleteParticipant,

    onSuccess: async () => {

      Toast.success(
        'Participant deleted successfully.'
      )

      await queryClient.invalidateQueries({
        queryKey: ['participants'],
      })

    },

    onError: () => {

      Toast.error(
        'Unable to delete participant.'
      )

    },

  })

  const rows = useMemo(() => {

    if (!data?.data) {
      return []
    }

    return data.data.filter((participant: any) =>

      participant.first_name
        ?.toLowerCase()
        .includes(search.toLowerCase()) ||

      participant.last_name
        ?.toLowerCase()
        .includes(search.toLowerCase()) ||

      participant.participant_code
        ?.toLowerCase()
        .includes(search.toLowerCase())

    )

  }, [data, search])

  const columns: GridColDef[] = [

    {
      field: 'participant_code',
      headerName: 'Code',
      flex: 1,
    },

    {
      field: 'first_name',
      headerName: 'First Name',
      flex: 1,
    },

    {
      field: 'last_name',
      headerName: 'Last Name',
      flex: 1,
    },

    {
      field: 'email',
      headerName: 'Email',
      flex: 2,
    },

    {
      field: 'status',
      headerName: 'Status',
      flex: 1,
    },

  ]

  if (isLoading) {
    return <AppSkeleton />
  }

  if (rows.length === 0) {

    return (

      <>

        <AppToolbar
          title="Participants"
          subtitle="Manage participants"
          buttonText="New Participant"
          onAdd={() => setCreateOpen(true)}
        >

          <SearchBar
            value={search}
            onChange={setSearch}
          />

        </AppToolbar>

        <EmptyState
          title="No Participants"
          subtitle="Create your first participant."
          button="New Participant"
          onClick={() => setCreateOpen(true)}
        />

        <ParticipantDialog
          open={createOpen}
          onClose={() => setCreateOpen(false)}
        />

      </>

    )

  }

  return (

    <>

      <AppToolbar
        title="Participants"
        subtitle="Manage participants"
        buttonText="New Participant"
        onAdd={() => setCreateOpen(true)}
        onRefresh={() => window.location.reload()}
        onExport={() => Toast.info('Export coming soon.')}
        onFilter={() => Toast.info('Filter coming soon.')}
      >

        <SearchBar
          value={search}
          onChange={setSearch}
        />

      </AppToolbar>

      <AppDataGrid
        rows={rows}
        columns={columns}
        onEdit={(participant) =>
          console.log('Edit', participant)
        }
        onDelete={(participant) => {
          setSelectedParticipant(participant)
          setConfirmOpen(true)
        }}
      />

      <ConfirmDialog
        open={confirmOpen}
        title="Delete Participant"
        message={`Delete "${selectedParticipant?.first_name} ${selectedParticipant?.last_name}"?`}
        loading={deleteMutation.isPending}
        onCancel={() => {
          setConfirmOpen(false)
          setSelectedParticipant(null)
        }}
        onConfirm={() => {

          if (!selectedParticipant) return

          deleteMutation.mutate(
            selectedParticipant.uuid,
            {
              onSuccess: () => {
                setConfirmOpen(false)
                setSelectedParticipant(null)
              },
            }
          )

        }}
      />

      <ParticipantDialog
        open={createOpen}
        onClose={() => setCreateOpen(false)}
      />

    </>

  )

}