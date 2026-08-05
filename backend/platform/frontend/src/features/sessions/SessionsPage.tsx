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

import SessionDialog from './SessionDialog'
import { deleteSession } from './api'
import { useSessions } from './hooks'

export default function SessionsPage() {
  const queryClient = useQueryClient()

  const { data, isLoading } = useSessions()

  const [search, setSearch] = useState('')
  const [createOpen, setCreateOpen] = useState(false)
  const [confirmOpen, setConfirmOpen] = useState(false)
  const [selectedSession, setSelectedSession] = useState<any>(null)

  const deleteMutation = useMutation({
    mutationFn: deleteSession,

    onSuccess: async () => {
      Toast.success('Session deleted successfully.')

      await queryClient.invalidateQueries({
        queryKey: ['sessions'],
      })
    },

    onError: () => {
      Toast.error('Unable to delete session.')
    },
  })

  const rows = useMemo(() => {
    if (!data?.data) return []

    return data.data.filter((session: any) => {
      return (
        session.title
          ?.toLowerCase()
          .includes(search.toLowerCase()) ||

        session.session_code
          ?.toLowerCase()
          .includes(search.toLowerCase())
      )
    })
  }, [data, search])

  const columns: GridColDef[] = [
    {
      field: 'session_code',
      headerName: 'Code',
      flex: 1,
    },
    {
      field: 'title',
      headerName: 'Title',
      flex: 2,
    },
    {
      field: 'session_number',
      headerName: '#',
      flex: .5,
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
          title="Sessions"
          subtitle="Manage program sessions"
          buttonText="New Session"
          onAdd={() => setCreateOpen(true)}
        >
          <SearchBar
            value={search}
            onChange={setSearch}
          />
        </AppToolbar>

        <EmptyState
          title="No Sessions"
          subtitle="Create your first session."
          button="New Session"
          onClick={() => setCreateOpen(true)}
        />

        <SessionDialog
          open={createOpen}
          onClose={() => setCreateOpen(false)}
        />
      </>
    )
  }

  return (
    <>
      <AppToolbar
        title="Sessions"
        subtitle="Manage program sessions"
        buttonText="New Session"
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
        onEdit={(session) =>
          console.log('Edit', session)
        }
        onDelete={(session) => {
          setSelectedSession(session)
          setConfirmOpen(true)
        }}
      />

      <ConfirmDialog
        open={confirmOpen}
        title="Delete Session"
        message={`Delete "${selectedSession?.title}"?`}
        loading={deleteMutation.isPending}
        onCancel={() => {
          setConfirmOpen(false)
          setSelectedSession(null)
        }}
        onConfirm={() => {

          if (!selectedSession) return

          deleteMutation.mutate(
            selectedSession.uuid,
            {
              onSuccess: () => {
                setConfirmOpen(false)
                setSelectedSession(null)
              },
            }
          )

        }}
      />

      <SessionDialog
        open={createOpen}
        onClose={() => setCreateOpen(false)}
      />
    </>
  )
}