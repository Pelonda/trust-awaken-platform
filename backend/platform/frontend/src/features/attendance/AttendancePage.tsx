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

import AttendanceDialog from './AttendanceDialog'
import { deleteAttendance } from './api'
import { useAttendance } from './hooks'

export default function AttendancePage() {

  const queryClient = useQueryClient()

  const { data, isLoading } = useAttendance()

  const [search, setSearch] = useState('')
  const [createOpen, setCreateOpen] = useState(false)
  const [confirmOpen, setConfirmOpen] = useState(false)
  const [selectedAttendance, setSelectedAttendance] = useState<any>(null)

  const deleteMutation = useMutation({
    mutationFn: deleteAttendance,

    onSuccess: async () => {

      Toast.success(
        'Attendance deleted.'
      )

      await queryClient.invalidateQueries({
        queryKey: ['attendance'],
      })

    },

    onError: () => {

      Toast.error(
        'Unable to delete attendance.'
      )

    },

  })

  const rows = useMemo(() => {

    if (!data?.data) return []

    return data.data.filter((item: any) =>
      item.participant_name
        ?.toLowerCase()
        .includes(search.toLowerCase())
    )

  }, [data, search])

  const columns: GridColDef[] = [
    {
      field: 'participant_name',
      headerName: 'Participant',
      flex: 2,
    },
    {
      field: 'session_title',
      headerName: 'Session',
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
          title="Attendance"
          subtitle="Manage attendance"
          buttonText="Record Attendance"
          onAdd={() => setCreateOpen(true)}
        >
          <SearchBar
            value={search}
            onChange={setSearch}
          />
        </AppToolbar>

        <EmptyState
          title="No Attendance"
          subtitle="Record attendance for a session."
          button="Record Attendance"
          onClick={() => setCreateOpen(true)}
        />

        <AttendanceDialog
          open={createOpen}
          onClose={() => setCreateOpen(false)}
        />
      </>
    )
  }

  return (
    <>
      <AppToolbar
        title="Attendance"
        subtitle="Manage attendance"
        buttonText="Record Attendance"
        onAdd={() => setCreateOpen(true)}
      >
        <SearchBar
          value={search}
          onChange={setSearch}
        />
      </AppToolbar>

      <AppDataGrid
        rows={rows}
        columns={columns}
        onEdit={(row) => console.log(row)}
        onDelete={(row) => {
          setSelectedAttendance(row)
          setConfirmOpen(true)
        }}
      />

      <ConfirmDialog
        open={confirmOpen}
        title="Delete Attendance"
        message="Delete this attendance record?"
        loading={deleteMutation.isPending}
        onCancel={() => {
          setConfirmOpen(false)
          setSelectedAttendance(null)
        }}
        onConfirm={() => {
          if (!selectedAttendance) return

          deleteMutation.mutate(selectedAttendance.uuid, {
            onSuccess: () => {
              setConfirmOpen(false)
              setSelectedAttendance(null)
            },
          })
        }}
      />

      <AttendanceDialog
        open={createOpen}
        onClose={() => setCreateOpen(false)}
      />
    </>
  )
}