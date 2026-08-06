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

import { deleteUser } from './api'
import { useUsers } from './hooks'
import UserDialog from './UserDialog'

export default function UsersPage() {

    const queryClient = useQueryClient()

    const { data, isLoading } = useUsers()

    const [search, setSearch] = useState('')

    const [createOpen, setCreateOpen] = useState(false)

    const [confirmOpen, setConfirmOpen] = useState(false)

    const [selectedUser, setSelectedUser] = useState<any>(null)

    const deleteMutation = useMutation({

        mutationFn: deleteUser,

        onSuccess: async () => {

            Toast.success('User deleted.')

            await queryClient.invalidateQueries({
                queryKey: ['users'],
            })

        },

        onError: () => {

            Toast.error('Unable to delete user.')

        },

    })

    const rows = useMemo(() => {

        if (!data?.data) return []

        return data.data.filter((user: any) =>

            user.name
                ?.toLowerCase()
                .includes(search.toLowerCase()) ||

            user.email
                ?.toLowerCase()
                .includes(search.toLowerCase())

        )

    }, [data, search])

    const columns: GridColDef[] = [

        {
            field: 'name',
            headerName: 'Name',
            flex: 2,
        },

        {
            field: 'email',
            headerName: 'Email',
            flex: 2,
        },

        {
            field: 'user_type',
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
                title="Users"
                subtitle="Manage platform users"
                buttonText="New User"
                onAdd={() => setCreateOpen(true)}
            >

                <SearchBar
                    value={search}
                    onChange={setSearch}
                />

                <EmptyState
                    title="No Users"
                    subtitle="Create your first user."
                    button="New User"
                    onClick={() => setCreateOpen(true)}
                />

                <UserDialog
                    open={createOpen}
                    onClose={() => setCreateOpen(false)}
                />

            </PageContainer>

        )

    }

    return (

        <PageContainer
            title="Users"
            subtitle="Manage platform users"
            buttonText="New User"
            onAdd={() => setCreateOpen(true)}
        >

            <SearchBar
                value={search}
                onChange={setSearch}
            />

            <AppDataGrid
                rows={rows}
                columns={columns}
                onEdit={(user) =>
                    console.log(user)
                }
                onDelete={(user) => {
                    setSelectedUser(user)
                    setConfirmOpen(true)
                }}
            />

            <ConfirmDialog
                open={confirmOpen}
                title="Delete User"
                message={`Delete "${selectedUser?.name}"?`}
                loading={deleteMutation.isPending}
                onCancel={() => {
                    setConfirmOpen(false)
                    setSelectedUser(null)
                }}
                onConfirm={() => {

                    if (!selectedUser) return

                    deleteMutation.mutate(
                        selectedUser.uuid,
                        {
                            onSuccess: () => {
                                setConfirmOpen(false)
                                setSelectedUser(null)
                            },
                        }
                    )

                }}
            />

            <UserDialog
                open={createOpen}
                onClose={() => setCreateOpen(false)}
            />

        </PageContainer>

    )

}