import { CircularProgress } from '@mui/material'
import { GridColDef } from '@mui/x-data-grid'
import { useMemo, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { useMutation, useQueryClient } from '@tanstack/react-query'

import DataTable from '../components/DataTable'
import PageHeader from '../components/PageHeader'
import SearchBar from '../components/SearchBar'

import { deleteProgram } from '../api/programs'
import { usePrograms } from '../hooks/usePrograms'

export default function ProgramsPage() {
  const navigate = useNavigate()

  const queryClient = useQueryClient()

  const { data, isLoading } = usePrograms()

  const [search, setSearch] = useState('')

  const deleteMutation = useMutation({
    mutationFn: deleteProgram,

    onSuccess: async () => {
      await queryClient.invalidateQueries({
        queryKey: ['programs'],
      })
    },
  })

  const rows = useMemo(() => {
    if (!data?.data) {
      return []
    }

    return data.data.filter((program: any) => {
      return (
        program.program_code
          ?.toLowerCase()
          .includes(search.toLowerCase()) ||
        program.title
          ?.toLowerCase()
          .includes(search.toLowerCase())
      )
    })
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
    return <CircularProgress />
  }

  return (
    <>
      <PageHeader
        title="Programs"
        button="New Program"
        onClick={() => navigate('/programs/create')}
      />

      <SearchBar
        value={search}
        onChange={setSearch}
      />

      <DataTable
        columns={columns}
        rows={rows}
        onEdit={(program) =>
          navigate(`/programs/${program.uuid}/edit`)
        }
        onDelete={(program) => {
          if (
            window.confirm(
              `Delete "${program.title}"?`
            )
          ) {
            deleteMutation.mutate(program.uuid)
          }
        }}
      />
    </>
  )
}