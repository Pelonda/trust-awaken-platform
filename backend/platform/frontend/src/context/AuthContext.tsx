import {
  createContext,
  useContext,
  useState,
  type ReactNode,
} from 'react'

interface User {
  id: number
  name: string
  email: string
}

interface AuthContextType {
  user: User | null
  token: string | null
  login: (user: User, token: string) => void
  logout: () => void
}

const AuthContext = createContext<AuthContextType | null>(null)

export function AuthProvider({
  children,
}: {
  children: ReactNode
}) {
  const [user, setUser] = useState<User | null>(() => {
    const value = localStorage.getItem('awaken_user')
    return value ? JSON.parse(value) : null
  })

  const [token, setToken] = useState<string | null>(() => {
    return localStorage.getItem('awaken_token')
  })

  function login(user: User, token: string) {
    setUser(user)
    setToken(token)

    localStorage.setItem(
      'awaken_user',
      JSON.stringify(user),
    )

    localStorage.setItem(
      'awaken_token',
      token,
    )
  }

  function logout() {
    setUser(null)
    setToken(null)

    localStorage.removeItem('awaken_user')
    localStorage.removeItem('awaken_token')
  }

  return (
    <AuthContext.Provider
      value={{
        user,
        token,
        login,
        logout,
      }}
    >
      {children}
    </AuthContext.Provider>
  )
}

export function useAuth() {
  const context = useContext(AuthContext)

  if (!context) {
    throw new Error(
      'useAuth must be used inside AuthProvider',
    )
  }

  return context
}