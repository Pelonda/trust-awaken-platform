import { useMemo, useState } from 'react'
import { useMutation, useQueryClient } from '@tanstack/react-query'
import type { GridColDef } from '@mui/x-data-grid'

import AppDataGrid from '../../components/tables/AppDataGrid'
import AppSkeleton from '../../components/common/AppSkeleton'
import ConfirmDialog from '../../components/dialogs/ConfirmDialog'
import EmptyState from '../../components/common/EmptyState'
import PageContainer from '../../components/common/PageContainer'
import SearchBar from '../../components/common/SearchBar'
import * as Toast from '../../components/common/AppToast'

import CredentialDialog from './CredentialDialog'

import {
  deleteCredential,
  downloadCredential,
  previewCredential,
} from './api'

import { useCredentials } from './hooks'

export default function CredentialsPage() {

  const queryClient = useQueryClient()

  const { data, isLoading } = useCredentials()

  const [search, setSearch] = useState('')

  const [createOpen, setCreateOpen] = useState(false)

  const [confirmOpen, setConfirmOpen] = useState(false)

  const [selectedCredential, setSelectedCredential] =
    useState<any>(null)

  const deleteMutation = useMutation({

    mutationFn: deleteCredential,

    onSuccess: async () => {

      Toast.success(
        'Credential deleted.'
      )

      await queryClient.invalidateQueries({
        queryKey: ['credentials'],
      })

    },

    onError: () => {

      Toast.error(
        'Unable to delete credential.'
      )

    },

  })

  const rows = useMemo(() => {

    if (!data?.data) {
      return []
    }

    return data.data.filter((credential: any) =>

      credential.credential_number
        ?.toLowerCase()
        .includes(search.toLowerCase())

    )

  }, [data, search])

  const columns: GridColDef[] = [

    {
      field: 'credential_number',
      headerName: 'Credential',
      flex: 2,
    },

    {
      field: 'credential_type',
      headerName: 'Type',
      flex: 1,
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

      <PageContainer
        title="Credentials"
        subtitle="Issue and manage credentials"
        buttonText="Issue Credential"
        onAdd={() => setCreateOpen(true)}
      >

        <SearchBar
          value={search}
          onChange={setSearch}
        />

        <EmptyState
          title="No Credentials"
          subtitle="Issue your first credential."
          button="Issue Credential"
          onClick={() => setCreateOpen(true)}
        />

        <CredentialDialog
          open={createOpen}
          onClose={() =>
            setCreateOpen(false)
          }
        />

      </PageContainer>

    )

  }

  return (

    <PageContainer
      title="Credentials"
      subtitle="Issue and manage credentials"
      buttonText="Issue Credential"
      onAdd={() => setCreateOpen(true)}
    >

      <SearchBar
        value={search}
        onChange={setSearch}
      />

      <AppDataGrid
        rows={rows}
        columns={columns}

        onPreview={(credential) =>
          previewCredential(
            credential.uuid
          )
        }

        onDownload={(credential) =>
          downloadCredential(
            credential.uuid
          )
        }

        onEdit={(credential) =>
          console.log(credential)
        }

        onDelete={(credential) => {

          setSelectedCredential(
            credential
          )

          setConfirmOpen(true)

        }}
      />

      <ConfirmDialog
        open={confirmOpen}
        title="Delete Credential"
        message={`Delete "${selectedCredential?.credential_number}"?`}
        loading={deleteMutation.isPending}
        onCancel={() => {

          setConfirmOpen(false)

          setSelectedCredential(null)

        }}
        onConfirm={() => {

          if (!selectedCredential) {
            return
          }

          deleteMutation.mutate(
            selectedCredential.uuid,
            {
              onSuccess: () => {

                setConfirmOpen(false)

                setSelectedCredential(null)

              },
            }
          )

        }}
      />

      <CredentialDialog
        open={createOpen}
        onClose={() =>
          setCreateOpen(false)
        }
      />

    </PageContainer>

  )

}