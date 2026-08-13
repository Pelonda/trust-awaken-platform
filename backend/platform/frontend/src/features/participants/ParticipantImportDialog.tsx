import {
  useMemo,
  useState,
} from 'react'

import {
  Alert,
  Box,
  Button,
  Chip,
  CircularProgress,
  Dialog,
  DialogActions,
  DialogContent,
  DialogTitle,
  Divider,
  FormControl,
  InputLabel,
  MenuItem,
  Paper,
  Select,
  Stack,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Typography,
} from '@mui/material'

import UploadFileIcon from '@mui/icons-material/UploadFile'
import CheckCircleIcon from '@mui/icons-material/CheckCircle'

import { useMutation, useQueryClient } from '@tanstack/react-query'

import * as Toast from '../../components/common/AppToast'

import { getPrograms } from '../programs/api'

import {
  commitParticipantImport,
  previewParticipantImport,
} from './api'

import type {
  ParticipantImportPreview,
} from './types'

interface Props {
  open: boolean
  onClose: () => void
}

interface ProgramOption {
  uuid: string
  program_code: string
  title: string
  status: string
}

export default function ParticipantImportDialog({
  open,
  onClose,
}: Props) {

  const queryClient =
    useQueryClient()

  const [
    programs,
    setPrograms,
  ] =
    useState<ProgramOption[]>([])

  const [
    programsLoaded,
    setProgramsLoaded,
  ] =
    useState(false)

  const [
    programsLoading,
    setProgramsLoading,
  ] =
    useState(false)

  const [
    programUuid,
    setProgramUuid,
  ] =
    useState('')

  const [
    file,
    setFile,
  ] =
    useState<File | null>(null)

  const [
    preview,
    setPreview,
  ] =
    useState<ParticipantImportPreview | null>(
      null,
    )

  const [
    resultMessage,
    setResultMessage,
  ] =
    useState<string | null>(null)

  const loadPrograms =
    async () => {

      if (
        programsLoaded ||
        programsLoading
      ) {
        return
      }

      setProgramsLoading(true)

      try {

        const response =
          await getPrograms()

        setPrograms(
          response.data ?? [],
        )

        setProgramsLoaded(true)

      } catch {

        Toast.error(
          'Unable to load programs.',
        )

      } finally {

        setProgramsLoading(false)

      }

    }

  /*
  |--------------------------------------------------------------------------
  | Preview Mutation
  |--------------------------------------------------------------------------
  */

  const previewMutation =
    useMutation({

      mutationFn:
        async () => {

          if (!file) {
            throw new Error(
              'Select a file first.',
            )
          }

          if (!programUuid) {
            throw new Error(
              'Select a program first.',
            )
          }

          return previewParticipantImport(
            file,
            programUuid,
          )
        },

      onSuccess:
        data => {

          setPreview(data)

          setResultMessage(null)

          if (
            data.summary.invalid_rows ===
            0
          ) {
            Toast.success(
              'Import file validated successfully.',
            )
          } else {
            Toast.info(
              `${data.summary.invalid_rows} row(s) need attention.`,
            )
          }

        },

      onError:
        (error: any) => {

          Toast.error(
            error?.response?.data?.message ??
            error?.message ??
            'Unable to preview import.',
          )

        },

    })

  /*
  |--------------------------------------------------------------------------
  | Commit Mutation
  |--------------------------------------------------------------------------
  */

  const commitMutation =
    useMutation({

      mutationFn:
        async () => {

          if (!file) {
            throw new Error(
              'Import file is missing.',
            )
          }

          if (!programUuid) {
            throw new Error(
              'Program is missing.',
            )
          }

          return commitParticipantImport(
            file,
            programUuid,
            {
              updateExisting: true,
              enrollmentStatus:
                'enrolled',
            },
          )
        },

      onSuccess:
        async data => {

          await queryClient.invalidateQueries({
            queryKey: [
              'participants',
            ],
          })

          await queryClient.invalidateQueries({
            queryKey: [
              'programs',
            ],
          })

          setResultMessage(
            [
              `${data.summary.created} created`,
              `${data.summary.updated} updated`,
              `${data.summary.enrolled} enrolled`,
              `${data.summary.skipped} skipped`,
            ].join(' • '),
          )

          Toast.success(
            'Participants imported and enrolled successfully.',
          )

        },

      onError:
        (error: any) => {

          Toast.error(
            error?.response?.data?.message ??
            error?.message ??
            'Unable to import participants.',
          )

        },

    })

  /*
  |--------------------------------------------------------------------------
  | Derived State
  |--------------------------------------------------------------------------
  */

  const canPreview =
    Boolean(
      file &&
      programUuid,
    )

  const canCommit =
    Boolean(
      preview &&
      preview.summary.valid_rows > 0 &&
      preview.summary.invalid_rows === 0 &&
      !commitMutation.isPending,
    )

  const selectedProgram =
    useMemo(
      () =>
        programs.find(
          program =>
            program.uuid ===
            programUuid,
        ) ?? null,
      [
        programs,
        programUuid,
      ],
    )

  /*
  |--------------------------------------------------------------------------
  | Reset
  |--------------------------------------------------------------------------
  */

  const reset =
    () => {

      setProgramUuid('')
      setFile(null)
      setPreview(null)
      setResultMessage(null)

      previewMutation.reset()
      commitMutation.reset()

    }

  const handleClose =
    () => {

      if (
        previewMutation.isPending ||
        commitMutation.isPending
      ) {
        return
      }

      reset()
      onClose()

    }

  /*
  |--------------------------------------------------------------------------
  | Render
  |--------------------------------------------------------------------------
  */

  return (

    <Dialog
      open={open}
      onClose={handleClose}
      fullWidth
      maxWidth="lg"
      TransitionProps={{
        onEntered:
          () => {
            void loadPrograms()
          },
      }}
    >

      <DialogTitle>

        Import Participants

        <Typography
          variant="body2"
          color="text.secondary"
          sx={{
            mt: 0.5,
          }}
        >
          Upload CSV or Excel, validate the rows,
          then automatically enroll participants
          into a program.
        </Typography>

      </DialogTitle>

      <DialogContent dividers>

        <Stack spacing={3}>

          {/* Program */}

          <Box>

            <Typography
              variant="subtitle2"
              sx={{
                mb: 1,
              }}
            >
              1. Select Program
            </Typography>

            <FormControl
              fullWidth
              size="small"
            >

              <InputLabel>
                Program
              </InputLabel>

              <Select
                label="Program"
                value={programUuid}
                disabled={
                  programsLoading ||
                  previewMutation.isPending ||
                  commitMutation.isPending
                }
                onOpen={() => {
                  void loadPrograms()
                }}
                onChange={
                  event => {

                    setProgramUuid(
                      event.target.value,
                    )

                    setPreview(null)
                    setResultMessage(null)

                  }
                }
              >

                {programs.map(
                  program => (

                    <MenuItem
                      key={program.uuid}
                      value={program.uuid}
                    >
                      {program.program_code}
                      {' — '}
                      {program.title}
                    </MenuItem>

                  ),
                )}

              </Select>

            </FormControl>

            {programsLoading && (

              <Stack
                direction="row"
                spacing={1}
                alignItems="center"
                sx={{
                  mt: 1,
                }}
              >
                <CircularProgress
                  size={16}
                />

                <Typography
                  variant="caption"
                  color="text.secondary"
                >
                  Loading programs...
                </Typography>
              </Stack>

            )}

          </Box>

          <Divider />

          {/* File */}

          <Box>

            <Typography
              variant="subtitle2"
              sx={{
                mb: 1,
              }}
            >
              2. Upload File
            </Typography>

            <Paper
              variant="outlined"
              sx={{
                p: 3,
                textAlign: 'center',
              }}
            >

              <Stack
                spacing={1.5}
                alignItems="center"
              >

                <UploadFileIcon
                  fontSize="large"
                />

                <Typography
                  variant="body1"
                  fontWeight={600}
                >
                  CSV / Excel
                </Typography>

                <Typography
                  variant="body2"
                  color="text.secondary"
                >
                  Supported:
                  {' '}
                  .csv, .xlsx, .xls
                </Typography>

                <Button
                  component="label"
                  variant="outlined"
                  disabled={
                    previewMutation.isPending ||
                    commitMutation.isPending
                  }
                >
                  Choose File

                  <input
                    hidden
                    type="file"
                    accept=".csv,.xlsx,.xls"
                    onChange={
                      event => {

                        const selected =
                          event
                            .target
                            .files?.[0] ??
                          null

                        setFile(
                          selected,
                        )

                        setPreview(null)
                        setResultMessage(null)

                        event.target.value =
                          ''

                      }
                    }
                  />

                </Button>

                {file && (

                  <Chip
                    label={
                      `${file.name} • ${Math.max(
                        1,
                        Math.round(
                          file.size / 1024,
                        ),
                      )} KB`
                    }
                    onDelete={
                      previewMutation.isPending ||
                      commitMutation.isPending
                        ? undefined
                        : () => {

                            setFile(null)
                            setPreview(null)
                            setResultMessage(null)

                          }
                    }
                  />

                )}

              </Stack>

            </Paper>

          </Box>

          {/* Preview Button */}

          <Button
            variant="contained"
            disabled={
              !canPreview ||
              previewMutation.isPending ||
              commitMutation.isPending
            }
            onClick={
              () =>
                previewMutation.mutate()
            }
          >

            {previewMutation.isPending
              ? (
                  <>
                    <CircularProgress
                      size={18}
                      sx={{
                        mr: 1,
                      }}
                    />
                    Validating...
                  </>
                )
              : 'Preview Import'}

          </Button>

          {/* Preview */}

          {preview && (

            <>

              <Divider />

              <Box>

                <Typography
                  variant="subtitle2"
                  sx={{
                    mb: 1.5,
                  }}
                >
                  3. Validation Summary
                </Typography>

                <Stack
                  direction={{
                    xs: 'column',
                    sm: 'row',
                  }}
                  spacing={1}
                  flexWrap="wrap"
                  useFlexGap
                >

                  <Chip
                    label={
                      `Total ${preview.summary.total_rows}`
                    }
                  />

                  <Chip
                    icon={
                      <CheckCircleIcon />
                    }
                    label={
                      `Valid ${preview.summary.valid_rows}`
                    }
                    color="success"
                  />

                  <Chip
  label={
    `Invalid ${preview.summary.invalid_rows}`
  }
  color={
    preview.summary.invalid_rows > 0
      ? 'error'
      : 'default'
  }
/>

                  <Chip
                    label={
                      `New ${preview.summary.new_participants}`
                    }
                    color="primary"
                  />

                  <Chip
                    label={
                      `Existing ${preview.summary.existing_participants}`
                    }
                  />

                  <Chip
                    label={
                      `Duplicates ${preview.summary.duplicates}`
                    }
                  />

                </Stack>

              </Box>

              {preview.summary.invalid_rows > 0 && (

                <Alert severity="error">
                  Fix invalid rows in the spreadsheet
                  and preview the file again before
                  importing.
                </Alert>

              )}

              {preview.summary.invalid_rows === 0 && (

                <Alert severity="success">
                  All rows are valid and ready to
                  import into
                  {' '}
                  <strong>
                    {selectedProgram?.title ??
                      preview.program.title}
                  </strong>.
                </Alert>

              )}

              {/* Rows */}

              <TableContainer
                component={Paper}
                variant="outlined"
                sx={{
                  maxHeight: 420,
                }}
              >

                <Table
                  stickyHeader
                  size="small"
                >

                  <TableHead>

                    <TableRow>

                      <TableCell>
                        Row
                      </TableCell>

                      <TableCell>
                        Code
                      </TableCell>

                      <TableCell>
                        Name
                      </TableCell>

                      <TableCell>
                        Email
                      </TableCell>

                      <TableCell>
                        Import Status
                      </TableCell>

                      <TableCell>
                        Errors
                      </TableCell>

                    </TableRow>

                  </TableHead>

                  <TableBody>

                    {preview.rows.map(
                      row => (

                        <TableRow
                          key={row.row}
                        >

                          <TableCell>
                            {row.row}
                          </TableCell>

                          <TableCell>
                            {row.data.participant_code ??
                              '—'}
                          </TableCell>

                          <TableCell>
                            {[
                              row.data.first_name,
                              row.data.last_name,
                            ]
                              .filter(Boolean)
                              .join(' ') ||
                              '—'}
                          </TableCell>

                          <TableCell>
                            {row.data.email ??
                              '—'}
                          </TableCell>

                          <TableCell>

                            {!row.valid
                              ? (
                                  <Chip
                                    size="small"
                                    label="Invalid"
                                    color="error"
                                  />
                                )
                              : row.duplicate
                                ? (
                                    <Chip
                                      size="small"
                                      label="Duplicate"
                                      color="warning"
                                    />
                                  )
                                : row.existing
                                  ? (
                                      <Chip
                                        size="small"
                                        label="Existing"
                                      />
                                    )
                                  : (
                                      <Chip
                                        size="small"
                                        label="New"
                                        color="success"
                                      />
                                    )}

                          </TableCell>

                          <TableCell>

                            {row.errors.length > 0
                              ? row.errors.join(
                                  ' ',
                                )
                              : '—'}

                          </TableCell>

                        </TableRow>

                      ),
                    )}

                  </TableBody>

                </Table>

              </TableContainer>

            </>

          )}

          {/* Result */}

          {resultMessage && (

            <Alert severity="success">
              Import completed:
              {' '}
              {resultMessage}
            </Alert>

          )}

        </Stack>

      </DialogContent>

      <DialogActions>

        <Button
          onClick={handleClose}
          disabled={
            previewMutation.isPending ||
            commitMutation.isPending
          }
        >
          {resultMessage
            ? 'Close'
            : 'Cancel'}
        </Button>

        {preview && !resultMessage && (

          <Button
            variant="contained"
            disabled={!canCommit}
            onClick={
              () =>
                commitMutation.mutate()
            }
          >

            {commitMutation.isPending
              ? (
                  <>
                    <CircularProgress
                      size={18}
                      sx={{
                        mr: 1,
                      }}
                    />
                    Importing...
                  </>
                )
              : `Import & Enroll (${preview.summary.valid_rows})`}

          </Button>

        )}

      </DialogActions>

    </Dialog>

  )
}