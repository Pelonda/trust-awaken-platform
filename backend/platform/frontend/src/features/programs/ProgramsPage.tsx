import type { GridColDef } from '@mui/x-data-grid'
import { useMemo, useState } from 'react'
import { useMutation, useQueryClient } from '@tanstack/react-query'

import DataTable from '../../components/tables/DataTable.old'
import AppToolbar from '../../components/common/AppToolbar'
import SearchBar from '../../components/common/SearchBar'
import EmptyState from '../../components/common/EmptyState'
import AppSkeleton from '../../components/common/AppSkeleton'
import ConfirmDialog from '../../components/dialogs/ConfirmDialog'
import * as Toast from '../../components/common/AppToast'

import ProgramDialog from './ProgramDialog'

import { deleteProgram } from './api'
import { usePrograms } from './hooks'

export default function ProgramsPage() {
  const queryClient = useQueryClient()

  const { data, isLoading } = usePrograms()

  const [search, setSearch] = useState('')

  const [createOpen, setCreateOpen] = useState(false)

  const [confirmOpen, setConfirmOpen] = useState(false)

  const [selectedProgram, setSelectedProgram] =
    useState<any>(null)

  const deleteMutation = useMutation({
    mutationFn: deleteProgram,

    onSuccess: async () => {
      Toast.success(
        'Program deleted successfully.'
      )

      await queryClient.invalidateQueries({
        queryKey: ['programs'],
      })
    },

    onError: () => {
      Toast.error(
        'Unable to delete program.'
      )
    },
  })

  const rows = useMemo(() => {

    if (!data?.data) {
      return []
    }

    return data.data.filter((program: any) =>

      program.program_code
        ?.toLowerCase()
        .includes(search.toLowerCase()) ||

      program.title
        ?.toLowerCase()
        .includes(search.toLowerCase())

    )

  }, [data, search])

  const columns: GridColDef[] = [

    {
      field: 'program_code',
      headerName: 'Code',
      flex: 1,
    },

    {
      field: 'title',
      headerName: 'Title',
      flex: 2,
    },

    {
      field: 'status',
      headerName: 'Status',
      flex: 1,
    },

    {
      field: 'delivery_mode',
      headerName: 'Mode',
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
          title="Programs"
          subtitle="Manage training programs"
          buttonText="New Program"
          onAdd={() => setCreateOpen(true)}
        >
          <SearchBar
            value={search}
            onChange={setSearch}
          />
        </AppToolbar>

        <EmptyState
          title="No Programs"
          subtitle="Create your first training program."
          button="Create Program"
          onClick={() => setCreateOpen(true)}
        />

        <ProgramDialog
          open={createOpen}
          onClose={() => setCreateOpen(false)}
        />
      </>
    )
  }

  return (
    <>
      <AppToolbar
        title="Programs"
        subtitle="Manage training programs"
        buttonText="New Program"
        onAdd={() => setCreateOpen(true)}
        onRefresh={() => window.location.reload()}
        onExport={() =>
          Toast.info('Export coming soon.')
        }
        onFilter={() =>
          Toast.info('Filter coming soon.')
        }
      >
        <SearchBar
          value={search}
          onChange={setSearch}
        />
      </AppToolbar>

      <DataTable
        columns={columns}
        rows={rows}
        onEdit={(program) => {
          console.log('Edit', program)
        }}
        onDelete={(program) => {
          setSelectedProgram(program)
          setConfirmOpen(true)
        }}
      />

      <ConfirmDialog
        open={confirmOpen}
        title="Delete Program"
        message={`Delete "${selectedProgram?.title}"?`}
        loading={deleteMutation.isPending}
        onCancel={() => {
          setConfirmOpen(false)
          setSelectedProgram(null)
        }}
        onConfirm={() => {

          if (!selectedProgram) {
            return
          }

          deleteMutation.mutate(
            selectedProgram.uuid,
            {
              onSuccess: () => {
                setConfirmOpen(false)
                setSelectedProgram(null)
              },
            }
          )

        }}
      />

      <ProgramDialog
        open={createOpen}
        onClose={() => setCreateOpen(false)}
      />
    </>
  )
}