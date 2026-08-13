import {
  useMemo,
  useState,
} from 'react'

import {
  useMutation,
  useQueryClient,
} from '@tanstack/react-query'

import type {
  GridColDef,
} from '@mui/x-data-grid'

import AppDataGrid from '../../components/tables/AppDataGrid'
import AppSkeleton from '../../components/common/AppSkeleton'
import ConfirmDialog from '../../components/dialogs/ConfirmDialog'
import EmptyState from '../../components/common/EmptyState'
import PageContainer from '../../components/common/PageContainer'
import SearchBar from '../../components/common/SearchBar'
import * as Toast from '../../components/common/AppToast'

import CredentialDialog from './CredentialDialog'

import {
  downloadCredential,
  previewCredential,
  restoreCredential,
  revokeCredential,
} from './api'

import {
  useCredentials,
} from './hooks'

import type {
  Credential,
} from './types'

export default function CredentialsPage() {
  const queryClient =
    useQueryClient()

  const {
    data,
    isLoading,
  } =
    useCredentials()

  const [
    search,
    setSearch,
  ] =
    useState('')

  const [
    createOpen,
    setCreateOpen,
  ] =
    useState(false)

  const [
    confirmOpen,
    setConfirmOpen,
  ] =
    useState(false)

  const [
    selectedCredential,
    setSelectedCredential,
  ] =
    useState<
      Credential | null
    >(null)

  /*
  |--------------------------------------------------------------------------
  | Lifecycle Mutation
  |--------------------------------------------------------------------------
  */

  const lifecycleMutation =
    useMutation({
      mutationFn:
        async (
          credential:
            Credential,
        ) => {
          if (
            credential.status ===
            'revoked'
          ) {
            return restoreCredential(
              credential.uuid,
            )
          }

          return revokeCredential(
            credential.uuid,
          )
        },

      onSuccess:
        async (
          _result,
          credential,
        ) => {
          if (
            credential.status ===
            'revoked'
          ) {
            Toast.success(
              'Credential restored.',
            )
          } else {
            Toast.success(
              'Credential revoked.',
            )
          }

          await queryClient
            .invalidateQueries({
              queryKey: [
                'credentials',
              ],
            })
        },

      onError:
        (
          _error,
          credential,
        ) => {
          if (
            credential.status ===
            'revoked'
          ) {
            Toast.error(
              'Unable to restore credential.',
            )
          } else {
            Toast.error(
              'Unable to revoke credential.',
            )
          }
        },
    })

  /*
  |--------------------------------------------------------------------------
  | Rows
  |--------------------------------------------------------------------------
  */

  const rows =
    useMemo(
      () => {
        const credentials =
          data?.data ??
          []

        const term =
          search
            .trim()
            .toLowerCase()

        if (!term) {
          return credentials
        }

        return credentials.filter(
          credential => {
            const number =
              credential
                .credential_number
                ?.toLowerCase()
                ?? ''

            const type =
              credential
                .credential_type
                ?.toLowerCase()
                ?? ''

            const status =
              credential
                .status
                ?.toLowerCase()
                ?? ''

            return (
              number.includes(
                term,
              ) ||
              type.includes(
                term,
              ) ||
              status.includes(
                term,
              )
            )
          },
        )
      },
      [
        data,
        search,
      ],
    )

  /*
  |--------------------------------------------------------------------------
  | Columns
  |--------------------------------------------------------------------------
  */

  const columns:
    GridColDef[] = [
      {
        field:
          'credential_number',

        headerName:
          'Credential',

        flex:
          2,
      },

      {
        field:
          'credential_type',

        headerName:
          'Type',

        flex:
          1,
      },

      {
        field:
          'status',

        headerName:
          'Status',

        flex:
          1,
      },
    ]

  /*
  |--------------------------------------------------------------------------
  | Preview
  |--------------------------------------------------------------------------
  */

  async function handlePreview(
    credential:
      Credential,
  ) {
    try {
      await previewCredential(
        credential.uuid,
      )
    } catch (
      error
    ) {
      console.error(
        error,
      )

      Toast.error(
        'Unable to preview credential.',
      )
    }
  }

  /*
  |--------------------------------------------------------------------------
  | Download
  |--------------------------------------------------------------------------
  */

  async function handleDownload(
    credential:
      Credential,
  ) {
    try {
      await downloadCredential(
        credential.uuid,
        credential
          .credential_number,
      )
    } catch (
      error
    ) {
      console.error(
        error,
      )

      Toast.error(
        'Unable to download credential.',
      )
    }
  }

  /*
  |--------------------------------------------------------------------------
  | Lifecycle Confirmation
  |--------------------------------------------------------------------------
  */

  function requestLifecycleAction(
    credential:
      Credential,
  ) {
    setSelectedCredential(
      credential,
    )

    setConfirmOpen(
      true,
    )
  }

  function closeConfirmation() {
    if (
      lifecycleMutation
        .isPending
    ) {
      return
    }

    setConfirmOpen(
      false,
    )

    setSelectedCredential(
      null,
    )
  }

  function confirmLifecycleAction() {
    if (
      !selectedCredential
    ) {
      return
    }

    lifecycleMutation.mutate(
      selectedCredential,
      {
        onSuccess: () => {
          setConfirmOpen(
            false,
          )

          setSelectedCredential(
            null,
          )
        },
      },
    )
  }

  /*
  |--------------------------------------------------------------------------
  | Loading
  |--------------------------------------------------------------------------
  */

  if (isLoading) {
    return (
      <AppSkeleton />
    )
  }

  /*
  |--------------------------------------------------------------------------
  | Empty
  |--------------------------------------------------------------------------
  */

  if (
    rows.length ===
    0
  ) {
    return (
      <PageContainer
        title="Credentials"
        subtitle="Issue and manage credentials"
        buttonText="Issue Credential"
        onAdd={() =>
          setCreateOpen(
            true,
          )
        }
      >
        <SearchBar
          value={
            search
          }
          onChange={
            setSearch
          }
        />

        <EmptyState
          title="No Credentials"
          subtitle={
            search
              ? 'No credentials match your search.'
              : 'Issue your first credential.'
          }
          button={
            search
              ? undefined
              : 'Issue Credential'
          }
          onClick={
            search
              ? undefined
              : () =>
                  setCreateOpen(
                    true,
                  )
          }
        />

        <CredentialDialog
          open={
            createOpen
          }
          onClose={() =>
            setCreateOpen(
              false,
            )
          }
        />
      </PageContainer>
    )
  }

  /*
  |--------------------------------------------------------------------------
  | Page
  |--------------------------------------------------------------------------
  */

  const restoring =
    selectedCredential
      ?.status ===
    'revoked'

  return (
    <PageContainer
      title="Credentials"
      subtitle="Issue and manage credentials"
      buttonText="Issue Credential"
      onAdd={() =>
        setCreateOpen(
          true,
        )
      }
    >
      <SearchBar
        value={
          search
        }
        onChange={
          setSearch
        }
      />

      <AppDataGrid
        rows={
          rows
        }
        columns={
          columns
        }

        onPreview={
          credential =>
            void handlePreview(
              credential,
            )
        }

        onDownload={
          credential =>
            void handleDownload(
              credential,
            )
        }

        /*
         * No Edit action.
         *
         * Issued credential identity,
         * participant, program and
         * immutable Fabric snapshot
         * should not be rewritten.
         */

        onLifecycleAction={
          requestLifecycleAction
        }

        lifecycleActionLabel={
          credential =>
            credential.status ===
            'revoked'
              ? 'Restore Credential'
              : 'Revoke Credential'
        }
      />

      <ConfirmDialog
        open={
          confirmOpen
        }

        title={
          restoring
            ? 'Restore Credential'
            : 'Revoke Credential'
        }

        message={
          restoring
            ? `Restore "${selectedCredential?.credential_number ?? ''}"?`
            : `Revoke "${selectedCredential?.credential_number ?? ''}"? The credential will remain in the audit history but will no longer be valid.`
        }

        loading={
          lifecycleMutation
            .isPending
        }

        onCancel={
          closeConfirmation
        }

        onConfirm={
          confirmLifecycleAction
        }
      />

      <CredentialDialog
        open={
          createOpen
        }
        onClose={() =>
          setCreateOpen(
            false,
          )
        }
      />
    </PageContainer>
  )
}