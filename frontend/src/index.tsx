import React from 'react'
import ReactDOM from 'react-dom'
import './index.css'
import App from './App'
import reportWebVitals from './reportWebVitals'
import { store } from './app/store'
import { Provider } from 'react-redux'


declare global {
  interface Window { satReactData: any, sat_textstrings: any }
}
const reactAppDATA = window.satReactData || {}
const { appSelector } = reactAppDATA
const appAnchorElement = document.querySelector(appSelector)

if (appAnchorElement) {
  ReactDOM.render(
    <React.StrictMode>
      <Provider store={store}>
        <App />
      </Provider>
    </React.StrictMode>,
    appAnchorElement,
  )
}

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals()
